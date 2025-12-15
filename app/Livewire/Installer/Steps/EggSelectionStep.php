<?php

namespace App\Livewire\Installer\Steps;

use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Artisan;

class EggSelectionStep
{
    public static function make(): Step
    {
        try {
            Artisan::call('p:egg:update-index');
            $eggs = cache()->get('eggs.index', []);
        } catch (\Throwable $t) {
            Notification::make()
                ->title(trans('installer.egg.exceptions.failed_to_update'))
                ->icon('tabler-egg')
                ->body($t->getMessage())
                ->danger()
                ->persistent()
                ->send();
            $eggs = [];
        }

        $categories = array_keys($eggs);

        $tabs = array_map(function (string $label) use ($eggs) {
            $id = str()->slug($label, '_');
            $eggCount = count($eggs[$label]);

            if ($eggCount === 0) {
                return Tab::make($id)
                    ->label($label)
                    ->schema([
                        CheckboxList::make("eggs.$id")
                            ->label('')
                            ->options(fn () => $eggs[$label])
                            ->columns(4),
                    ]);
            }

            return Tab::make($id)
                ->label($label)
                ->badge($eggCount)
                ->schema([
                    CheckboxList::make("eggs.$id")
                        ->hiddenLabel()
                        ->options(fn () => $eggs[$label])
                        ->searchable()
                        ->bulkToggleable()
                        ->columns(4),
                ]);
        }, $categories);

        if (empty($tabs)) {
            $tabs[] = Tab::make('no_eggs')
                ->label(trans('installer.egg.no_eggs'))
                ->schema([
                    trans('installer.egg.exceptions.no_eggs'),
                ]);
        }

        return Step::make('egg')
            ->label(trans('installer.egg.title'))
            ->columnSpanFull()
            ->schema([
                Tabs::make('egg_tabs')
                    ->tabs($tabs),
            ]);
    }
}
