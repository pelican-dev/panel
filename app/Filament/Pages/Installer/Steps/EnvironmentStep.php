<?php

namespace App\Filament\Pages\Installer\Steps;

use App\Filament\Pages\Installer\PanelInstaller;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Set;

class EnvironmentStep
{
    public const SESSION_DRIVERS = [
        'file' => 'Filesystem',
        'database' => 'Database',
        'cookie' => 'Cookie',
        'redis' => 'Redis',
    ];

    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('environment')
            ->label('Environment')
            ->columns()
            ->schema([
                TextInput::make('env_general.APP_NAME')
                    ->label('App Name')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('This will be the Name of your Panel.')
                    ->required()
                    ->default(config('app.name')),
                TextInput::make('env_general.APP_URL')
                    ->label('App URL')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('This will be the URL you access your Panel from.')
                    ->required()
                    ->default(url(''))
                    ->live()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('env_general.SESSION_SECURE_COOKIE', str_starts_with($state, 'https://') ? 'true' : 'false')),
                TextInput::make('env_general.SESSION_SECURE_COOKIE')
                    ->hidden()
                    ->default(str_starts_with(url(''), 'https://') ? 'true' : 'false'),
                ToggleButtons::make('env_session.SESSION_DRIVER')
                    ->label('Session Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for storing sessions. We recommend "Filesystem" or "Database".')
                    ->required()
                    ->inline()
                    ->options(self::SESSION_DRIVERS)
                    ->default(config('session.driver')),
            ])
            ->afterValidation(fn () => $installer->writeToEnv('env_general'));
    }
}
