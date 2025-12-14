<?php

namespace App\Livewire\Installer\Steps;

use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\HtmlString;

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
            $eggCount = count($eggs[$label] ?? []);
            $repeatableItems = array_map(fn ($downloadUrl, $name) => ['name' => $name, 'download_url' => $downloadUrl], array_keys($eggs[$label] ?? []), array_values($eggs[$label] ?? []));

            if ($eggCount === 0) {
                return Tab::make($id)
                    ->label($label)
                    ->schema([
                        CheckboxList::make("eggs.$id")
                            ->label('')
                            ->options(fn () => $eggs[$label] ?? [])
                            ->columns(4),
                    ]);
            }

            return Tab::make($id)
                ->label($label)
                ->badge($eggCount)
                ->schema([
                    new HtmlString('<div class="egg-controls" style="margin-bottom:.25rem" data-values="' . base64_encode(json_encode(array_keys($eggs[$label] ?? []))) . '"><button type="button" class="egg-select-all" data-tab="' . $id . '">Select all</button>&nbsp;<button type="button" class="egg-deselect-all" data-tab="' . $id . '">Deselect all</button></div>'),

                    RepeatableEntry::make("repeatable.$id")
                        ->grid(4)
                        ->hiddenLabel()
                        ->state(fn () => $repeatableItems)
                        ->schema([
                            TextEntry::make('name')
                                ->hiddenLabel(),
                            TextEntry::make('download_url')
                                ->hiddenLabel()
                                ->extraEntryWrapperAttributes(['class' => 'egg-download-url', 'style' => 'display:none']),
                        ]),

                    CheckboxList::make("eggs.$id")
                        ->extraAttributes(['style' => 'display:none'])
                        ->hiddenLabel()
                        ->options(fn () => $eggs[$label] ?? [])
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
        $script = new HtmlString(view('installer.egg-repeatable-script')->render());

        return Step::make('egg')
            ->label(trans('installer.egg.title'))
            ->columnSpanFull()
            ->schema([
                Tabs::make('egg_tabs')
                    ->tabs($tabs),
                $script,
            ]);
    }
}
