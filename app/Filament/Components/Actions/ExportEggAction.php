<?php

namespace App\Filament\Components\Actions;

use App\Enums\EggFormat;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
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

        $this->authorize(fn () => auth()->user()->can('export egg'));

        $this->modalHeading(fn (Egg $egg) => trans('filament-actions::export.modal.actions.export.label') . '  ' . $egg->name);

        $this->modalIcon($this->icon);

        $this->form([
            Placeholder::make('')
                ->label(fn (Egg $egg) => trans('admin/egg.export.modal', ['egg' => $egg->name])),
        ]);

        $this->modalFooterActionsAlignment(Alignment::Center);

        $this->modalFooterActions([
            Action::make('json')
                ->label(trans('admin/egg.export.as') . ' .json')
                ->action(fn (EggExporterService $service, Egg $egg) => response()->streamDownload(function () use ($service, $egg) {
                    echo $service->handle($egg->id, EggFormat::JSON);
                }, 'egg-' . $egg->getKebabName() . '.json'))
                ->close(),
            Action::make('yaml')
                ->label(trans('admin/egg.export.as') . ' .yaml')
                ->action(fn (EggExporterService $service, Egg $egg) => response()->streamDownload(function () use ($service, $egg) {
                    echo $service->handle($egg->id, EggFormat::YAML);
                }, 'egg-' . $egg->getKebabName() . '.yaml'))
                ->close(),
        ]);
    }
}
