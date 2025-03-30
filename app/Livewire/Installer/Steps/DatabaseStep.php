<?php

namespace App\Livewire\Installer\Steps;

use App\Livewire\Installer\PanelInstaller;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;

class DatabaseStep
{
    public const DATABASE_DRIVERS = [
        'sqlite' => 'SQLite',
        'mariadb' => 'MariaDB',
        'mysql' => 'MySQL',
        'pgsql' => 'PostgreSQL',
    ];

    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('database')
            ->label('Database')
            ->columns()
            ->schema([
                ToggleButtons::make('env_database.DB_CONNECTION')
                    ->label('Database Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for the panel database. We recommend "SQLite".')
                    ->required()
                    ->inline()
                    ->options(self::DATABASE_DRIVERS)
                    ->default(config('database.default'))
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        $set('env_database.DB_DATABASE', $state === 'sqlite' ? 'database.sqlite' : 'panel');

                        switch ($state) {
                            case 'sqlite':
                                $set('env_database.DB_HOST', null);
                                $set('env_database.DB_PORT', null);
                                $set('env_database.DB_USERNAME', null);
                                $set('env_database.DB_PASSWORD', null);
                                break;
                            case 'mariadb':
                            case 'mysql':
                                $set('env_database.DB_HOST', $get('env_database.DB_HOST') ?? '127.0.0.1');
                                $set('env_database.DB_USERNAME', $get('env_database.DB_USERNAME') ?? 'pelican');
                                $set('env_database.DB_PORT', '3306');
                                break;
                            case 'pgsql':
                                $set('env_database.DB_HOST', $get('env_database.DB_HOST') ?? '127.0.0.1');
                                $set('env_database.DB_USERNAME', $get('env_database.DB_USERNAME') ?? 'pelican');
                                $set('env_database.DB_PORT', '5432');
                                break;
                        }
                    }),
                TextInput::make('env_database.DB_DATABASE')
                    ->label(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite' ? 'Database Path' : 'Database Name')
                    ->placeholder(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite' ? 'database.sqlite' : 'panel')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite' ? 'The path of your .sqlite file relative to the database folder.' : 'The name of the panel database.')
                    ->required()
                    ->default('database.sqlite'),
                TextInput::make('env_database.DB_HOST')
                    ->label('Database Host')
                    ->placeholder('127.0.0.1')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The host of your database. Make sure it is reachable.')
                    ->required(fn (Get $get) => $get('env_database.DB_CONNECTION') !== 'sqlite')
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_PORT')
                    ->label('Database Port')
                    ->placeholder('3306')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The port of your database.')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(65535)
                    ->required(fn (Get $get) => $get('env_database.DB_CONNECTION') !== 'sqlite')
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_USERNAME')
                    ->label('Database Username')
                    ->placeholder('pelican')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The name of your database user.')
                    ->required(fn (Get $get) => $get('env_database.DB_CONNECTION') !== 'sqlite')
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_PASSWORD')
                    ->label('Database Password')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The password of your database user. Can be empty.')
                    ->password()
                    ->revealable()
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
            ])
            ->afterValidation(function (Get $get) use ($installer) {
                $driver = $get('env_database.DB_CONNECTION');

                if (!self::testConnection($driver, $get('env_database.DB_HOST'), $get('env_database.DB_PORT'), $get('env_database.DB_DATABASE'), $get('env_database.DB_USERNAME'), $get('env_database.DB_PASSWORD'))) {
                    throw new Halt('Database connection failed');
                }

                $installer->writeToEnv('env_database');
            });
    }

    private static function testConnection(string $driver, ?string $host, null|string|int $port, ?string $database, ?string $username, ?string $password): bool
    {
        if ($driver === 'sqlite') {
            return true;
        }

        try {
            config()->set('database.connections._panel_install_test', [
                'driver' => $driver,
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'username' => $username,
                'password' => $password,
                'collation' => 'utf8mb4_unicode_ci',
                'strict' => true,
            ]);

            DB::connection('_panel_install_test')->getPdo();
        } catch (Exception $exception) {
            DB::disconnect('_panel_install_test');

            Notification::make()
                ->title('Database connection failed')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return false;
        }

        return true;
    }
}
