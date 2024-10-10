<?php

namespace App\Filament\Pages\Installer\Steps;

use App\Filament\Pages\Installer\PanelInstaller;
use App\Traits\EnvironmentWriterTrait;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Set;

class EnvironmentStep
{
    use EnvironmentWriterTrait;

    public const CACHE_DRIVERS = [
        'file' => 'Filesystem',
        'redis' => 'Redis',
    ];

    public const SESSION_DRIVERS = [
        'file' => 'Filesystem',
        'database' => 'Database',
        'cookie' => 'Cookie',
        'redis' => 'Redis',
    ];

    public const QUEUE_DRIVERS = [
        'database' => 'Database',
        'sync' => 'Sync',
        'redis' => 'Redis',
    ];

    public const DATABASE_DRIVERS = [
        'sqlite' => 'SQLite',
        'mariadb' => 'MariaDB',
        'mysql' => 'MySQL',
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
                ToggleButtons::make('env_general.CACHE_STORE')
                    ->label('Cache Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for caching. We recommend "Filesystem".')
                    ->required()
                    ->inline()
                    ->options(self::CACHE_DRIVERS)
                    ->default(config('cache.default', 'file')),
                ToggleButtons::make('env_general.SESSION_DRIVER')
                    ->label('Session Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for storing sessions. We recommend "Filesystem" or "Database".')
                    ->required()
                    ->inline()
                    ->options(self::SESSION_DRIVERS)
                    ->default(config('session.driver', 'file')),
                ToggleButtons::make('env_general.QUEUE_CONNECTION')
                    ->label('Queue Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for handling queues. We recommend "Database".')
                    ->required()
                    ->inline()
                    ->options(self::QUEUE_DRIVERS)
                    ->default(config('queue.default', 'database')),
                ToggleButtons::make('env_general.DB_CONNECTION')
                    ->label('Database Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for the panel database. We recommend "SQLite".')
                    ->required()
                    ->inline()
                    ->options(self::DATABASE_DRIVERS)
                    ->default(config('database.default', 'sqlite')),
            ])
            ->afterValidation(fn () => $installer->writeToEnv('env_general'));
    }
}
