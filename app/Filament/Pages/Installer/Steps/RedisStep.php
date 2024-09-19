<?php

namespace App\Filament\Pages\Installer\Steps;

use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Redis;

class RedisStep
{
    public static function make(): Step
    {
        return Step::make('redis')
            ->label('Redis')
            ->columns()
            ->schema([
                TextInput::make('env.REDIS_HOST')
                    ->label('Redis Host')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The host of your redis server. Make sure it is reachable.')
                    ->required()
                    ->default(config('database.redis.default.host')),
                TextInput::make('env.REDIS_PORT')
                    ->label('Redis Port')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The port of your redis server.')
                    ->required()
                    ->default(config('database.redis.default.port')),
                TextInput::make('env.REDIS_USERNAME')
                    ->label('Redis Username')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The name of your redis user. Can be empty')
                    ->default(config('database.redis.default.username')),
                TextInput::make('env.REDIS_PASSWORD')
                    ->label('Redis Password')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The password for your redis user. Can be empty.')
                    ->password()
                    ->revealable()
                    ->default(config('database.redis.default.password')),
            ])
            ->afterValidation(function (Get $get) {
                try {
                    config()->set('database.redis._panel_install_test', [
                        'host' => $get('env.REDIS_HOST'),
                        'username' => $get('env.REDIS_USERNAME'),
                        'password' => $get('env.REDIS_PASSWORD'),
                        'port' => $get('env.REDIS_PORT'),
                    ]);

                    Redis::connection('_panel_install_test')->command('ping');
                } catch (Exception $exception) {
                    Notification::make()
                        ->title('Redis connection failed')
                        ->body($exception->getMessage())
                        ->danger()
                        ->send();

                    throw new Halt('Redis connection failed');
                }
            });
    }
}
