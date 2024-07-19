<?php

namespace App\Filament\Pages\Installer\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\DatabaseManager;
use PDOException;

class DatabaseStep
{
    public static function make(): Step
    {
        return Step::make('database')
            ->label('Database')
            ->schema([
                TextInput::make('env.DB_DATABASE')
                    ->label(fn (Get $get) => $get('env.DB_CONNECTION') === 'sqlite' ? 'Database Path' : 'Database Name')
                    ->hint(fn (Get $get) => $get('env.DB_CONNECTION') === 'sqlite' ? 'The path of your .sqlite file relative to the database folder.' : 'The name of the panel database.')
                    ->required()
                    ->default(fn (Get $get) => env('DB_DATABASE', $get('env.DB_CONNECTION') === 'sqlite' ? 'database.sqlite' : 'panel')),
                TextInput::make('env.DB_HOST')
                    ->label('Database Host')
                    ->hint('The host of your database. Make sure it is reachable.')
                    ->required()
                    ->default(env('DB_HOST', '127.0.0.1'))
                    ->hidden(fn (Get $get) => $get('env.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env.DB_PORT')
                    ->label('Database Port')
                    ->hint('The port of your database.')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(65535)
                    ->default(env('DB_PORT', 3306))
                    ->hidden(fn (Get $get) => $get('env.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env.DB_USERNAME')
                    ->label('Database Username')
                    ->hint('The name of your database user.')
                    ->required()
                    ->default(env('DB_USERNAME', 'pelican'))
                    ->hidden(fn (Get $get) => $get('env.DB_CONNECTION') === 'sqlite'),
                TextInput::make('env.DB_PASSWORD')
                    ->label('Database Password')
                    ->hint('The password of your database user. Can be empty.')
                    ->password()
                    ->revealable()
                    ->default(env('DB_PASSWORD'))
                    ->hidden(fn (Get $get) => $get('env.DB_CONNECTION') === 'sqlite'),
            ])
            ->afterValidation(function (Get $get) {
                $driver = $get('env.DB_CONNECTION');
                if ($driver !== 'sqlite') {
                    /** @var DatabaseManager $database */
                    $database = app(DatabaseManager::class);

                    try {
                        config()->set('database.connections._panel_install_test', [
                            'driver' => $driver,
                            'host' => $get('env.DB_HOST'),
                            'port' => $get('env.DB_PORT'),
                            'database' => $get('env.DB_DATABASE'),
                            'username' => $get('env.DB_USERNAME'),
                            'password' => $get('env.DB_PASSWORD'),
                            'charset' => 'utf8mb4',
                            'collation' => 'utf8mb4_unicode_ci',
                            'strict' => true,
                        ]);

                        $database->connection('_panel_install_test')->getPdo();
                    } catch (PDOException $exception) {
                        Notification::make()
                            ->title('Database connection failed')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        $database->disconnect('_panel_install_test');

                        throw new Halt('Database connection failed');
                    }
                }
            });
    }
}
