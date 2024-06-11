<?php

namespace App\Services\Servers;

use Illuminate\Support\Arr;
use App\Models\Server;
use Illuminate\Database\ConnectionInterface;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Exceptions\Http\Connection\DaemonConnectionException;

class BuildModificationService
{
    /**
     * BuildModificationService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DaemonServerRepository $daemonServerRepository,
        private ServerConfigurationStructureService $structureService
    ) {
    }

    /**
     * Change the build details for a specified server.
     *
     * @throws \Throwable
     * @throws \App\Exceptions\DisplayException
     */
    public function handle(Server $server, array $data): Server
    {
        /** @var \App\Models\Server $server */
        $server = $this->connection->transaction(function () use ($server, $data) {
            if (!isset($data['oom_killer']) && isset($data['oom_disabled'])) {
                $data['oom_killer'] = !$data['oom_disabled'];
            }

            // If any of these values are passed through in the data array go ahead and set them correctly on the server model.
            $merge = Arr::only($data, ['oom_killer', 'memory', 'swap', 'io', 'cpu', 'threads', 'disk', 'ports']);

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
            } catch (DaemonConnectionException $exception) {
                logger()->warning($exception, ['server_id' => $server->id]);
            }
        }

        return $server;
    }
}
