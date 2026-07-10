<?php

namespace App\Services\Servers;

use App\Enums\ServerState;
use App\Models\Server;

class ToggleInstallService
{
    public function handle(Server $server): void
    {
        abort_if($server->isFailedInstall(), 500, trans('exceptions.server.marked_as_failed'));

        $server->status = $server->isInstalled() ? ServerState::Installing : null;
        $server->save();
    }
}
