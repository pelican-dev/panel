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
use Illuminate\Foundation\Application;
use Illuminate\Redis\RedisManager;

class CacheStep
{
    public const CACHE_DRIVERS = [
        'file' => 'Filesystem',
        'redis' => 'Redis',
    ];

    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('cache')
            ->label(trans('installer.cache.title'))
            ->columns()
            ->schema([
                ToggleButtons::make('env_cache.CACHE_STORE')
                    ->label(trans('installer.cache.driver'))
                    ->hintIcon('tabler-question-mark', trans('installer.cache.driver_help'))
                    ->required()
                    ->inline()
                    ->options(self::CACHE_DRIVERS)
                    ->default(config('cache.default'))
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                        if ($state !== 'redis') {
                            $set('env_cache.REDIS_HOST', null);
                            $set('env_cache.REDIS_PORT', null);
                            $set('env_cache.REDIS_USERNAME', null);
                            $set('env_cache.REDIS_PASSWORD', null);
                        } else {
                            $set('env_cache.REDIS_HOST', $get('env_cache.REDIS_HOST') ?? '127.0.0.1');
                            $set('env_cache.REDIS_PORT', $get('env_cache.REDIS_PORT') ?? '6379');
                            $set('env_cache.REDIS_USERNAME', null);
                        }
                    }),
                TextInput::make('env_cache.REDIS_HOST')
                    ->label(trans('installer.cache.fields.host'))
                    ->placeholder('127.0.0.1')
                    ->hintIcon('tabler-question-mark', trans('installer.cache.fields.host_help'))
                    ->required(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis')
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.host') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
                TextInput::make('env_cache.REDIS_PORT')
                    ->label(trans('installer.cache.fields.port'))
                    ->placeholder('6379')
                    ->hintIcon('tabler-question-mark', trans('installer.cache.fields.port_help'))
                    ->required(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis')
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.port') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
                TextInput::make('env_cache.REDIS_USERNAME')
                    ->label(trans('installer.cache.fields.username'))
                    ->hintIcon('tabler-question-mark', trans('installer.cache.fields.username_help'))
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.username') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
                TextInput::make('env_cache.REDIS_PASSWORD')
                    ->label(trans('installer.cache.fields.password'))
                    ->hintIcon('tabler-question-mark', trans('installer.cache.fields.password_help'))
                    ->password()
                    ->revealable()
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.password') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
            ])
            ->afterValidation(function (Get $get, Application $app) use ($installer) {
                $driver = $get('env_cache.CACHE_STORE');

                if (!self::testConnection($app, $driver, $get('env_cache.REDIS_HOST'), $get('env_cache.REDIS_PORT'), $get('env_cache.REDIS_USERNAME'), $get('env_cache.REDIS_PASSWORD'))) {
                    throw new Halt('Redis connection failed');
                }

                $installer->writeToEnv('env_cache');
            });
    }

    private static function testConnection(Application $app, string $driver, ?string $host, null|string|int $port, ?string $username, ?string $password): bool
    {
        if ($driver !== 'redis') {
            return true;
        }

        try {
            $redis = new RedisManager($app, 'predis', [
                'default' => [
                    'host' => $host,
                    'port' => $port,
                    'username' => $username,
                    'password' => $password,
                ],
            ]);

            $redis->connection()->command('ping');
        } catch (Exception $exception) {
            Notification::make()
                ->title(trans('installer.cache.exception'))
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return false;
        }

        return true;
    }
}
