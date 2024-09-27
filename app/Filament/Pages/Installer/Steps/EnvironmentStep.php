<?php

namespace App\Filament\Pages\Installer\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Set;

class EnvironmentStep
{
    public const CACHE_DRIVERS = [
        'file' => 'Filesystem',
        'redis' => 'Redis',
    ];

    public const SESSION_DRIVERS = [
        'file' => 'Filesystem',
        'redis' => 'Redis',
        'database' => 'Database',
        'cookie' => 'Cookie',
    ];

    public const QUEUE_DRIVERS = [
        'sync' => 'Sync',
        'database' => 'Database',
        'redis' => 'Redis',
    ];

    public const DATABASE_DRIVERS = [
        'sqlite' => 'SQLite',
        'mariadb' => 'MariaDB',
        'mysql' => 'MySQL',
    ];

    public static function make(): Step
    {
        return Step::make('environment')
            ->label('Environment')
            ->columns()
            ->schema([
                TextInput::make('env.APP_NAME')
                    ->label('App Name')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('This will be the Name of your Panel.')
                    ->required()
                    ->default(config('app.name')),
                TextInput::make('env.APP_URL')
                    ->label('App URL')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('This will be the URL you access your Panel from.')
                    ->required()
                    ->default(config('app.url'))
                    ->live()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('env.SESSION_SECURE_COOKIE', str_starts_with($state, 'https://'))),
                Toggle::make('env.SESSION_SECURE_COOKIE')
                    ->hidden()
                    ->default(env('SESSION_SECURE_COOKIE')),
                ToggleButtons::make('env.CACHE_STORE')
                    ->label('Cache Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for caching. We recommend "Filesystem".')
                    ->required()
                    ->inline()
                    ->options(self::CACHE_DRIVERS)
                    ->default(config('cache.default', 'file')),
                ToggleButtons::make('env.SESSION_DRIVER')
                    ->label('Session Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for storing sessions. We recommend "Filesystem" or "Database".')
                    ->required()
                    ->inline()
                    ->options(self::SESSION_DRIVERS)
                    ->default(config('session.driver', 'file')),
                ToggleButtons::make('env.QUEUE_CONNECTION')
                    ->label('Queue Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for handling queues. We recommend "Sync" or "Database".')
                    ->required()
                    ->inline()
                    ->options(self::QUEUE_DRIVERS)
                    ->default(config('queue.default', 'database')),
                ToggleButtons::make('env.DB_CONNECTION')
                    ->label('Database Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for the panel database. We recommend "SQLite".')
                    ->required()
                    ->inline()
                    ->options(self::DATABASE_DRIVERS)
                    ->default(config('database.default', 'sqlite')),
            ]);
    }
}
