<?php

namespace App\Services\Servers;

use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Traits\Services\ReturnsUpdatedModels;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Throwable;

class DetailsModificationService
{
    use ReturnsUpdatedModels;

    /**
     * DetailsModificationService constructor.
     */
    public function __construct(private ConnectionInterface $connection, private DaemonServerRepository $serverRepository) {}

    /**
     * Update the details for a single server instance.
     *
     * @param array{
     *     external_id: int,
     *     owner_id: int,
     *     name: string,
     *     description?: ?string
     * } $data
     *
     * @throws Throwable
     */
    public function handle(Server $server, array $data): Server
    {
        return $this->connection->transaction(function () use ($data, $server) {
            $owner = $server->owner_id;

            $server->forceFill([
                'external_id' => Arr::get($data, 'external_id'),
                'owner_id' => Arr::get($data, 'owner_id'),
                'name' => Arr::get($data, 'name'),
                'description' => Arr::get($data, 'description') ?? '',
            ])->saveOrFail();

            // If the owner_id value is changed we need to revoke any tokens that exist for the server
            // on the daemon instance so that the old owner no longer has any permission to access the
            // websockets.
            if ($server->owner_id !== $owner) {
                try {
                    $this->serverRepository->setServer($server)->deauthorize($server->user->uuid);
                } catch (ConnectionException) {
                    // Do nothing. A failure here is not ideal, but it is likely to be caused by daemon
                    // being offline, or in an entirely broken state. Remember, these tokens reset every
                    // few minutes by default, we're just trying to help it along a little quicker.
                }
            }

            return $server;
        });
    }
}
