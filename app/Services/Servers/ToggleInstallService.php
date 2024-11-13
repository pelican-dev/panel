<?php

namespace App\Services\Servers;

use App\Enums\ServerState;
use App\Models\Server;

class ToggleInstallService
{
    public function handle(Server $server): void
    {
        if ($server->status === ServerState::InstallFailed) {
            abort(500, trans('admin/server.exceptions.marked_as_failed'));
        }

        $server->status = $server->isInstalled() ? ServerState::Installing : null;
        $server->save();
    }
}
