<?php

namespace App\Filament\Components\Actions;

use App\Models\Server;
use App\Services\Servers\Sharing\ServerConfigExporterService;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;

class ExportServerConfigAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'export_config';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Export');

        $this->iconSize(IconSize::ExtraLarge);

        $this->tooltip('Export server configuration to YAML file');

        $this->authorize(fn () => user()?->can('view server'));

        $this->modalHeading(fn (Server $server) => 'Export Configuration: ' . $server->name);

        $this->modalDescription('Export the server\'s configuration, settings, limits, allocations, and variable values to a YAML file.');

        $this->modalFooterActionsAlignment(Alignment::Center);

        $this->schema([
            Toggle::make('include_description')
                ->label('Include Description')
                ->helperText('Export the server description')
                ->default(true),
            Toggle::make('include_allocations')
                ->label('Include Allocations')
                ->helperText('Export IP addresses and ports assigned to the server')
                ->default(true),
            Toggle::make('include_variable_values')
                ->label('Include Variable Values')
                ->helperText('Export environment variable values')
                ->default(true),
        ]);

        $this->action(fn (ServerConfigExporterService $service, Server $server, array $data) => response()->streamDownload(
            function () use ($service, $server, $data) {
                echo $service->handle($server, $data);
            },
            'server-' . str($server->name)->kebab()->lower()->trim() . '.yaml',
            [
                'Content-Type' => 'application/x-yaml',
            ]
        ));
    }
}
