<?php

namespace App\Services\Servers;

use App\Enums\ServerState;
use App\Models\Server;
use App\Repositories\Daemon\DaemonServerRepository;
use Illuminate\Database\ConnectionInterface;
use Throwable;

class ReinstallServerService
{
    /**
     * ReinstallService constructor.
     */
    public function __construct(
        private ConnectionInterface $connection,
        private DaemonServerRepository $daemonServerRepository
    ) {}

    /**
     * Reinstall a server on the remote daemon.
     *
     * @throws Throwable
     */
    public function handle(Server $server): Server
    {
        return $this->connection->transaction(function () use ($server) {
            $server->fill(['status' => ServerState::Installing])->save();

            $this->daemonServerRepository->setServer($server)->reinstall();

            return $server->refresh();
        });
    }
}
