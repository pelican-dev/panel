<?php

namespace App\Filament\Components\Actions;

use App\Enums\EggFormat;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\Alignment;

class ExportEggAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'export';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('filament-actions::export.modal.actions.export.label'));

        $this->tableIcon('tabler-download');

        $this->authorize(fn () => auth()->user()->can('export egg'));

        $this->modalHeading(fn (Egg $egg) => trans('filament-actions::export.modal.actions.export.label') . '  ' . $egg->name);

        $this->modalIcon($this->icon);

        $this->schema([
            TextEntry::make('label')
                ->hiddenLabel()
                ->state(fn (Egg $egg) => trans('admin/egg.export.modal', ['egg' => $egg->name])),
        ]);

        $this->modalFooterActionsAlignment(Alignment::Center);

        $this->modalFooterActions([ //TODO: Close modal after clicking ->close() does not allow action to preform before closing modal
            Action::make('json')
                ->label(trans('admin/egg.export.as', ['format' => 'json']))
                ->action(fn (EggExporterService $service, Egg $egg) => response()->streamDownload(function () use ($service, $egg) {
                    echo $service->handle($egg->id, EggFormat::JSON);
                }, 'egg-' . $egg->getKebabName() . '.json')),
            Action::make('yaml')
                ->label(trans('admin/egg.export.as', ['format' => 'yaml']))
                ->action(fn (EggExporterService $service, Egg $egg) => response()->streamDownload(function () use ($service, $egg) {
                    echo $service->handle($egg->id, EggFormat::YAML);
                }, 'egg-' . $egg->getKebabName() . '.yaml')),
        ]);
    }
}
