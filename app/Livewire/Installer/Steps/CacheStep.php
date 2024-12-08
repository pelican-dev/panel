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
            ->label('Cache')
            ->columns()
            ->schema([
                ToggleButtons::make('env_cache.CACHE_STORE')
                    ->label('Cache Driver')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The driver used for caching. We recommend "Filesystem".')
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
                    ->label('Redis Host')
                    ->placeholder('127.0.0.1')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The host of your redis server. Make sure it is reachable.')
                    ->required(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis')
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.host') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
                TextInput::make('env_cache.REDIS_PORT')
                    ->label('Redis Port')
                    ->placeholder('6379')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The port of your redis server.')
                    ->required(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis')
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.port') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
                TextInput::make('env_cache.REDIS_USERNAME')
                    ->label('Redis Username')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The name of your redis user. Can be empty')
                    ->default(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis' ? config('database.redis.default.username') : null)
                    ->visible(fn (Get $get) => $get('env_cache.CACHE_STORE') === 'redis'),
                TextInput::make('env_cache.REDIS_PASSWORD')
                    ->label('Redis Password')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The password for your redis user. Can be empty.')
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
                ->title('Redis connection failed')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return false;
        }

        return true;
    }
}
