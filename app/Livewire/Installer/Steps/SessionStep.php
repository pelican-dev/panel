<?php

namespace App\Livewire\Installer\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;

class SessionStep
{
    public const SESSION_DRIVERS = [
        'file' => 'Filesystem',
        'database' => 'Database',
        'cookie' => 'Cookie',
        'redis' => 'Redis',
    ];

    public static function make(): Step
    {
        return Step::make('session')
            ->label(trans('installer.session.title'))
            ->schema([
                ToggleButtons::make('env_session.SESSION_DRIVER')
                    ->label(trans('installer.session.driver'))
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip(trans('installer.session.driver_help'))
                    ->required()
                    ->inline()
                    ->options(self::SESSION_DRIVERS)
                    ->disableOptionWhen(fn ($value, Get $get) => $value === 'redis' && $get('env_cache.CACHE_STORE') !== 'redis')
                    ->default(config('session.driver')),
                TextInput::make('env_session.SESSION_SECURE_COOKIE')
                    ->hidden()
                    ->default(request()->isSecure()),
            ]);
    }
}
