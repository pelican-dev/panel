<?php

namespace App\Services\Servers;

use App\Models\Server;
use App\Enums\ServerState;

class ToggleInstallService
{
    public function handle(Server $server): void
    {
        if ($server->status === ServerState::InstallFailed) {
            abort(500, trans('exceptions.server.marked_as_failed'));
        }

        $server->status = $server->isInstalled() ? ServerState::Installing : null;
        $server->save();
    }
}
