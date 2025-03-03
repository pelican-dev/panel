<?php

namespace App\Services\Servers;

use Illuminate\Support\Arr;
use App\Models\Server;
use App\Models\Allocation;
use Illuminate\Database\ConnectionInterface;
use App\Exceptions\DisplayException;
use App\Repositories\Daemon\DaemonServerRepository;
use Illuminate\Http\Client\ConnectionException;

class BuildModificationService
{
    /**
     * BuildModificationService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DaemonServerRepository $daemonServerRepository,
        private ServerConfigurationStructureService $structureService
    ) {}

    /**
     * Change the build details for a specified server.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws \Throwable
     * @throws \App\Exceptions\DisplayException
     */
    public function handle(Server $server, array $data): Server
    {
        /** @var \App\Models\Server $server */
        $server = $this->connection->transaction(function () use ($server, $data) {
            $this->processAllocations($server, $data);

            if (isset($data['allocation_id']) && $data['allocation_id'] != $server->allocation_id) {
                $existingAllocation = $server->allocations()->findOrFail($data['allocation_id']);

                throw_unless($existingAllocation, new DisplayException('The requested default allocation is not currently assigned to this server.'));
            }

            if (!isset($data['oom_killer']) && isset($data['oom_disabled'])) {
                $data['oom_killer'] = !$data['oom_disabled'];
            }

            // If any of these values are passed through in the data array go ahead and set them correctly on the server model.
            $merge = Arr::only($data, ['oom_killer', 'memory', 'swap', 'io', 'cpu', 'threads', 'disk', 'allocation_id']);

            $server->forceFill(array_merge($merge, [
                'database_limit' => Arr::get($data, 'database_limit', 0) ?? null,
                'allocation_limit' => Arr::get($data, 'allocation_limit', 0) ?? null,
                'backup_limit' => Arr::get($data, 'backup_limit', 0) ?? 0,
            ]))->saveOrFail();

            return $server->refresh();
        });

        $updateData = $this->structureService->handle($server);

        // Because daemon always fetches an updated configuration from the Panel when booting
        // a server this type of exception can be safely "ignored" and just written to the logs.
        // Ideally this request succeeds, so we can apply resource modifications on the fly, but
        // if it fails we can just continue on as normal.
        if (!empty($updateData['build'])) {
            try {
                $this->daemonServerRepository->setServer($server)->sync();
            } catch (ConnectionException $exception) {
                logger()->warning($exception, ['server_id' => $server->id]);
            }
        }

        return $server;
    }

    /**
     * Process the allocations being assigned in the data and ensure they are available for a server.
     *
     * @param array{
     *     add_allocations?: array<int>,
     *     remove_allocations?: array<int>,
     *     allocation_id?: int,
     *     oom_killer?: bool,
     *     oom_disabled?: bool,
     * } $data
     *
     * @throws \App\Exceptions\DisplayException
     */
    private function processAllocations(Server $server, array &$data): void
    {
        if (empty($data['add_allocations']) && empty($data['remove_allocations'])) {
            return;
        }

        // Handle the addition of allocations to this server. Only assign allocations that are not currently
        // assigned to a different server, and only allocations on the same node as the server.
        if (!empty($data['add_allocations'])) {
            $query = Allocation::query()
                ->where('node_id', $server->node_id)
                ->whereIn('id', $data['add_allocations'])
                ->whereNull('server_id');

            // Keep track of all the allocations we're just now adding so that we can use the first
            // one to reset the default allocation to.
            $freshlyAllocated = $query->first()?->id;

            $query->update(['server_id' => $server->id, 'notes' => null]);
        }

        if (!empty($data['remove_allocations'])) {
            foreach ($data['remove_allocations'] as $allocation) {
                // If we are attempting to remove the default allocation for the server, see if we can reassign
                // to the first provided value in add_allocations. If there is no new first allocation then we
                // will throw an exception back.
                if ($allocation === ($data['allocation_id'] ?? $server->allocation_id)) {
                    if (empty($freshlyAllocated)) {
                        throw new DisplayException('You are attempting to delete the default allocation for this server but there is no fallback allocation to use.');
                    }

                    // Update the default allocation to be the first allocation that we are creating.
                    $data['allocation_id'] = $freshlyAllocated;
                }
            }

            // Remove any of the allocations we got that are currently assigned to this server on
            // this node. Also set the notes to null, otherwise when re-allocated to a new server those
            // notes will be carried over.
            Allocation::query()->where('node_id', $server->node_id)
                ->where('server_id', $server->id)
                // Only remove the allocations that we didn't also attempt to add to the server...
                ->whereIn('id', array_diff($data['remove_allocations'], $data['add_allocations'] ?? []))
                ->update([
                    'notes' => null,
                    'server_id' => null,
                ]);
        }
    }
}
