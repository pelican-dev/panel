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

    public function getLabel(): string
    {
        return trans('server/startup.preview');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(function (Get $get, Set $set, Server $server) {
            $active = $get('previewing');
            $set('previewing', !$active);
            $set('startup', $active ? $server->startup : fn (Server $server, StartupCommandService $service) => $service->handle($server));
        });
    }
}
