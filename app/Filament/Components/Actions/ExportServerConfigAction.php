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

        $this->label(trans('filament-actions::export.modal.actions.export.label'));

        $this->iconSize(IconSize::ExtraLarge);

        $this->tooltip(trans('admin/server.import_export.export_tooltip'));

        $this->authorize(fn () => user()?->can('view server'));

        $this->modalHeading(fn (Server $server) => trans('admin/server.import_export.export_heading', ['name' => $server->name]));

        $this->modalDescription(trans('admin/server.import_export.export_description'));

        $this->modalFooterActionsAlignment(Alignment::Center);

        $this->schema([
            Toggle::make('include_description')
                ->label(trans('admin/server.import_export.include_description'))
                ->helperText(trans('admin/server.import_export.include_description_help'))
                ->default(true),
            Toggle::make('include_allocations')
                ->label(trans('admin/server.import_export.include_allocations'))
                ->helperText(trans('admin/server.import_export.include_allocations_help'))
                ->default(true),
            Toggle::make('include_variable_values')
                ->label(trans('admin/server.import_export.include_variables'))
                ->helperText(trans('admin/server.import_export.include_variables_help'))
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
