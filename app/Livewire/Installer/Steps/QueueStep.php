<?php

namespace App\Livewire\Installer\Steps;

use App\Livewire\Installer\PanelInstaller;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class QueueStep
{
    public const QUEUE_DRIVERS = [
        'database' => 'Database',
        'redis' => 'Redis',
        'sync' => 'Sync',
    ];

    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('queue')
            ->label(trans('installer.queue.title'))
            ->columns()
            ->schema([
                ToggleButtons::make('env_queue.QUEUE_CONNECTION')
                    ->label(trans('installer.queue.driver'))
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip(trans('installer.queue.driver_help'))
                    ->required()
                    ->inline()
                    ->options(self::QUEUE_DRIVERS)
                    ->disableOptionWhen(fn ($value, Get $get) => $value === 'redis' && $get('env_cache.CACHE_STORE') !== 'redis')
                    ->default(config('queue.default')),
                Toggle::make('done')
                    ->label(trans('installer.queue.fields.done'))
                    ->accepted(fn () => !@file_exists('/.dockerenv'))
                    ->inline(false)
                    ->validationMessages([
                        'accepted' => trans('installer.queue.fields.done_validation'),
                    ])
                    ->hidden(fn () => @file_exists('/.dockerenv')),
                TextInput::make('crontab')
                    ->label(new HtmlString(trans('installer.queue.fields.crontab')))
                    ->disabled()
                    ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                    ->default('(crontab -l -u www-data 2>/dev/null; echo "* * * * * php ' . base_path() . '/artisan schedule:run >> /dev/null 2>&1") | crontab -u www-data -')
                    ->hidden(fn () => @file_exists('/.dockerenv'))
                    ->columnSpanFull(),
                TextInput::make('queueService')
                    ->label(new HtmlString(trans('installer.queue.fields.service')))
                    ->disabled()
                    ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                    ->default('sudo php ' . base_path() . '/artisan p:environment:queue-service')
                    ->hidden(fn () => @file_exists('/.dockerenv'))
                    ->columnSpanFull(),
            ])
            ->afterValidation(fn () => $installer->writeToEnv('env_queue'));
    }
}
