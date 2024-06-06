<?php

namespace App\Services\Servers;

use App\Exceptions\Http\Connection\DaemonConnectionException;
use App\Models\Allocation;
use App\Models\Node;
use App\Models\Server;
use App\Models\ServerTransfer;
use App\Services\Nodes\NodeJWTService;
use Carbon\CarbonImmutable;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Http;
use Lcobucci\JWT\Token\Plain;

class TransferServerService
{
    /**
     * TransferService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private NodeJWTService $nodeJWTService,
    ) {
    }

    private function notify(Server $server, Plain $token): void
    {
        try {
            Http::daemon($server->node)->post('/api/transfer', [
                'json' => [
                    'server_id' => $server->uuid,
                    'url' => $server->node->getConnectionAddress() . "/api/servers/$server->uuid/archive",
                    'token' => 'Bearer ' . $token->toString(),
                    'server' => [
                        'uuid' => $server->uuid,
                        'start_on_completion' => false,
                    ],
                ],
            ])->toPsrResponse();
        } catch (TransferException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    /**
     * Starts a transfer of a server to a new node.
     *
     * @throws \Throwable
     */
    public function handle(Server $server, array $data): bool
    {
        $node_id = $data['node_id'];
        $allocation_id = intval($data['allocation_id']);
        $additional_allocations = array_map(intval(...), $data['allocation_additional'] ?? []);

        // Check if the node is viable for the transfer.
        $node = Node::query()
            ->select(['nodes.id', 'nodes.fqdn', 'nodes.scheme', 'nodes.daemon_token', 'nodes.daemon_listen', 'nodes.memory', 'nodes.disk', 'nodes.cpu', 'nodes.memory_overallocate', 'nodes.disk_overallocate', 'nodes.cpu_overallocate'])
            ->withSum('servers', 'disk')
            ->withSum('servers', 'memory')
            ->withSum('servers', 'cpu')
            ->leftJoin('servers', 'servers.node_id', '=', 'nodes.id')
            ->where('nodes.id', $node_id)
            ->first();

        if (!$node->isViable($server->memory, $server->disk, $server->cpu)) {
            return false;
        }

        $server->validateTransferState();

        $this->connection->transaction(function () use ($server, $node_id, $allocation_id, $additional_allocations) {
            // Create a new ServerTransfer entry.
            $transfer = new ServerTransfer();

            $transfer->server_id = $server->id;
            $transfer->old_node = $server->node_id;
            $transfer->new_node = $node_id;
            $transfer->old_allocation = $server->allocation_id;
            $transfer->new_allocation = $allocation_id;
            $transfer->old_additional_allocations = $server->allocations->where('id', '!=', $server->allocation_id)->pluck('id')->all();
            $transfer->new_additional_allocations = $additional_allocations;

            $transfer->save();

            // Add the allocations to the server, so they cannot be automatically assigned while the transfer is in progress.
            $this->assignAllocationsToServer($server, $node_id, $allocation_id, $additional_allocations);

            // Generate a token for the destination node that the source node can use to authenticate with.
            $token = $this->nodeJWTService
                ->setExpiresAt(CarbonImmutable::now()->addMinutes(15))
                ->setSubject($server->uuid)
                ->handle($transfer->newNode, $server->uuid, 'sha256');

            // Notify the source node of the pending outgoing transfer.
            $this->notify($server, $token);

            return $transfer;
        });

        return true;
    }

    /**
     * Assigns the specified allocations to the specified server.
     */
    private function assignAllocationsToServer(Server $server, int $node_id, int $allocation_id, array $additional_allocations)
    {
        $allocations = $additional_allocations;
        $allocations[] = $allocation_id;

        $node = Node::query()->findOrFail($node_id);
        $unassigned = $node->allocations()
            ->whereNull('server_id')
            ->pluck('id')
            ->toArray();

        $updateIds = [];
        foreach ($allocations as $allocation) {
            if (!in_array($allocation, $unassigned)) {
                continue;
            }

            $updateIds[] = $allocation;
        }

        if (!empty($updateIds)) {
            Allocation::query()->whereIn('id', $updateIds)->update(['server_id' => $server->id]);
        }
    }
}
