<?php

namespace App\Filament\Pages\Installer\Steps;

use App\Filament\Pages\Installer\PanelInstaller;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\DB;

class DatabaseStep
{
    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('database')
            ->label('Database')
            ->columns()
            ->schema([
                TextInput::make('env_database.DB_DATABASE')
                    ->label(fn (Get $get) => $get('env_general.DB_CONNECTION') === 'sqlite' ? 'Database Path' : 'Database Name')
                    ->columnSpanFull()
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip(fn (Get $get) => $get('env_general.DB_CONNECTION') === 'sqlite' ? 'The path of your .sqlite file relative to the database folder.' : 'The name of the panel database.')
                    ->required()
                    ->default(fn (Get $get) => env('DB_DATABASE', $get('env_general.DB_CONNECTION') === 'sqlite' ? 'database.sqlite' : 'panel')),
                TextInput::make('env_database.DB_HOST')
                    ->label('Database Host')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The host of your database. Make sure it is reachable.')
                    ->required(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite')
                    ->default(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite' ? env('DB_HOST', '127.0.0.1') : null)
                    ->hidden(fn (Get $get) => $get('env_general.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_PORT')
                    ->label('Database Port')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The port of your database.')
                    ->required(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(65535)
                    ->default(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite' ? env('DB_PORT', 3306) : null)
                    ->hidden(fn (Get $get) => $get('env_general.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_USERNAME')
                    ->label('Database Username')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The name of your database user.')
                    ->required(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite')
                    ->default(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite' ? env('DB_USERNAME', 'pelican') : null)
                    ->hidden(fn (Get $get) => $get('env_general.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env_database.DB_PASSWORD')
                    ->label('Database Password')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The password of your database user. Can be empty.')
                    ->password()
                    ->revealable()
                    ->default(fn (Get $get) => $get('env_general.DB_CONNECTION') !== 'sqlite' ? env('DB_PASSWORD') : null)
                    ->hidden(fn (Get $get) => $get('env_general.DB_CONNECTION') === 'sqlite'),
            ])
            ->afterValidation(function (Get $get) use ($installer) {
                $driver = $get('env_general.DB_CONNECTION');

                if (!self::testConnection($driver, $get('env_database.DB_HOST'), $get('env_database.DB_PORT'), $get('env_database.DB_DATABASE'), $get('env_database.DB_USERNAME'), $get('env_database.DB_PASSWORD'))) {
                    throw new Halt('Database connection failed');
                }

                $installer->writeToEnv('env_database');

                $installer->runMigrations($driver);
            });
    }

    private static function testConnection(string $driver, $host, $port, $database, $username, $password): bool
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
                'charset' => 'utf8mb4',
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
