<?php

namespace App\Filament\Components\Actions;

use App\Models\Server;
use App\Services\Servers\StartupCommandService;
use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PreviewStartupAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn (Get $get) => $get('previewing') ? trans('server/startup.disable_preview') : trans('server/startup.enable_preview'));

        $this->action(function (Get $get, Set $set, Server $server) {
            $previewing = !$get('previewing');

            $set('previewing', $previewing);
            $set('startup', !$previewing ? $server->startup : fn (Server $server, StartupCommandService $service) => $service->handle($server, $server->startup));
        });
    }
}
