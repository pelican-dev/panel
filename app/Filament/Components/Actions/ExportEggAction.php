<?php

namespace App\Filament\Components\Actions;

use App\Enums\EggFormat;
use App\Models\Egg;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;

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

        $this->iconButton();

        $this->icon('tabler-download');

        $this->tableIcon('tabler-download');

        $this->iconSize(IconSize::ExtraLarge);

        $this->authorize(fn () => user()?->can('export egg'));

        $this->modalHeading(fn (Egg $egg) => trans('filament-actions::export.modal.actions.export.label') . '  ' . $egg->name);

        $this->modalIcon($this->icon);

        $this->schema([
            TextEntry::make('label')
                ->hiddenLabel()
                ->state(fn (Egg $egg) => trans('admin/egg.export.modal', ['egg' => $egg->name])),
        ]);

        $this->modalFooterActionsAlignment(Alignment::Center);

        $this->modalFooterActions([
            Action::make('json')
                ->label(trans('admin/egg.export.as', ['format' => 'json']))
                ->url(fn (Egg $egg) => route('api.application.eggs.eggs.export', ['egg' => $egg, 'format' => EggFormat::JSON->value]), true)
                ->close(),
            Action::make('yaml')
                ->label(trans('admin/egg.export.as', ['format' => 'yaml']))
                ->url(fn (Egg $egg) => route('api.application.eggs.eggs.export', ['egg' => $egg, 'format' => EggFormat::YAML->value]), true)
                ->close(),
        ]);
    }
}
