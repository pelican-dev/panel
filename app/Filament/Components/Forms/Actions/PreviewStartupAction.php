<?php

namespace App\Filament\Components\Forms\Actions;

use App\Models\Server;
use App\Services\Servers\StartupCommandService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;

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
