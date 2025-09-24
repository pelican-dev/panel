<?php

namespace App\Livewire\Installer\Steps;

use App\Livewire\Installer\PanelInstaller;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;
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
            ->label(trans('installer.database.title'))
            ->columns()
            ->schema([
                ToggleButtons::make('env_database.DB_CONNECTION')
                    ->label(trans('installer.database.driver'))
                    ->hintIcon('tabler-question-mark', trans('installer.database.driver_help'))
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
                    ->label(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite' ? trans('installer.database.fields.path') : trans('installer.database.fields.name'))
                    ->placeholder(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite' ? 'database.sqlite' : 'panel')
                    ->hintIcon('tabler-question-mark', fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite' ? trans('installer.database.fields.path_help') : trans('installer.database.fields.name_help'))
                    ->required()
                    ->default('database.sqlite'),
                TextInput::make('env_database.DB_HOST')
                    ->label(trans('installer.database.fields.host'))
                    ->placeholder('127.0.0.1')
                    ->hintIcon('tabler-question-mark', trans('installer.database.fields.host_help'))
                    ->required(fn (Get $get) => $get('env_database.DB_CONNECTION') !== 'sqlite')
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_PORT')
                    ->label(trans('installer.database.fields.port'))
                    ->placeholder('3306')
                    ->hintIcon('tabler-question-mark', trans('installer.database.fields.port_help'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(65535)
                    ->required(fn (Get $get) => $get('env_database.DB_CONNECTION') !== 'sqlite')
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_USERNAME')
                    ->label(trans('installer.database.fields.username'))
                    ->placeholder('pelican')
                    ->hintIcon('tabler-question-mark', trans('installer.database.fields.username_help'))
                    ->required(fn (Get $get) => $get('env_database.DB_CONNECTION') !== 'sqlite')
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_PASSWORD')
                    ->label(trans('installer.database.fields.password'))
                    ->hintIcon('tabler-question-mark', trans('installer.database.fields.password_help'))
                    ->password()
                    ->revealable()
                    ->hidden(fn (Get $get) => $get('env_database.DB_CONNECTION') === 'sqlite'),
            ])
            ->afterValidation(function (Get $get) use ($installer) {
                $driver = $get('env_database.DB_CONNECTION');

                if (!self::testConnection($driver, $get('env_database.DB_HOST'), $get('env_database.DB_PORT'), $get('env_database.DB_DATABASE'), $get('env_database.DB_USERNAME'), $get('env_database.DB_PASSWORD'))) {
                    throw new Halt(trans('installer.database.exceptions.connection'));
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
                ->title(trans('installer.database.exceptions.connection'))
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return false;
        }

        return true;
    }
}
