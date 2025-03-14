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
            ->label('Queue')
            ->columns()
            ->schema([
                ToggleButtons::make('env_queue.QUEUE_CONNECTION')
                    ->label('Queue Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for handling queues. We recommend "Database".')
                    ->required()
                    ->inline()
                    ->options(self::QUEUE_DRIVERS)
                    ->disableOptionWhen(fn ($value, Get $get) => $value === 'redis' && $get('env_cache.CACHE_STORE') !== 'redis')
                    ->default(config('queue.default')),
                Toggle::make('done')
                    ->label('I have done both steps below.')
                    ->accepted(fn () => !@file_exists('/.dockerenv'))
                    ->inline(false)
                    ->validationMessages([
                        'accepted' => 'You need to do both steps before continuing!',
                    ])
                    ->hidden(fn () => @file_exists('/.dockerenv')),
                TextInput::make('crontab')
                    ->label(new HtmlString('Run the following command to set up your crontab. Note that <code>www-data</code> is your webserver user. On some systems this username might be different!'))
                    ->disabled()
                    ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                    ->default('(crontab -l -u www-data 2>/dev/null; echo "* * * * * php ' . base_path() . '/artisan schedule:run >> /dev/null 2>&1") | crontab -u www-data -')
                    ->hidden(fn () => @file_exists('/.dockerenv'))
                    ->columnSpanFull(),
                TextInput::make('queueService')
                    ->label(new HtmlString('To setup the queue worker service you simply have to run the following command.'))
                    ->disabled()
                    ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                    ->default('sudo php ' . base_path() . '/artisan p:environment:queue-service')
                    ->hidden(fn () => @file_exists('/.dockerenv'))
                    ->columnSpanFull(),
            ])
            ->afterValidation(fn () => $installer->writeToEnv('env_queue'));
    }
}
