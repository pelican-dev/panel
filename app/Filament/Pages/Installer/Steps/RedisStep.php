<?php

namespace App\Filament\Pages\Installer\Steps;

use App\Filament\Pages\Installer\PanelInstaller;
use App\Traits\EnvironmentWriterTrait;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Foundation\Application;
use Illuminate\Redis\RedisManager;

class RedisStep
{
    use EnvironmentWriterTrait;

    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('redis')
            ->label('Redis')
            ->columns()
            ->schema([
                TextInput::make('env_redis.REDIS_HOST')
                    ->label('Redis Host')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The host of your redis server. Make sure it is reachable.')
                    ->required()
                    ->default(config('database.redis.default.host')),
                TextInput::make('env_redis.REDIS_PORT')
                    ->label('Redis Port')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The port of your redis server.')
                    ->required()
                    ->default(config('database.redis.default.port')),
                TextInput::make('env_redis.REDIS_USERNAME')
                    ->label('Redis Username')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The name of your redis user. Can be empty')
                    ->default(config('database.redis.default.username')),
                TextInput::make('env_redis.REDIS_PASSWORD')
                    ->label('Redis Password')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The password for your redis user. Can be empty.')
                    ->password()
                    ->revealable()
                    ->default(config('database.redis.default.password')),
            ])
            ->afterValidation(function (Get $get, Application $app) use ($installer) {
                if (!self::testConnection($app, $get('env_redis.REDIS_HOST'), $get('env_redis.REDIS_PORT'), $get('env_redis.REDIS_USERNAME'), $get('env_redis.REDIS_PASSWORD'))) {
                    throw new Halt('Redis connection failed');
                }

                $installer->writeToEnv('env_redis');
            });
    }

    private static function testConnection(Application $app, string $host, null|string|int $port, ?string $username, ?string $password): bool
    {
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
