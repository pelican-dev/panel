<?php

namespace App\Filament\Pages\Installer\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

class RedisStep
{
    public static function make(): Step
    {
        return Step::make('redis')
            ->label('Redis')
            ->schema([
                TextInput::make('env.REDIS_HOST')
                    ->label('Redis Host')
                    ->hint('The host of your redis server. Make sure it is reachable.')
                    ->required()
                    ->default(config('database.redis.default.host')),
                TextInput::make('env.REDIS_PORT')
                    ->label('Redis Port')
                    ->hint('The port of your redis server.')
                    ->required()
                    ->default(config('database.redis.default.port')),
                TextInput::make('env.REDIS_PASSWORD')
                    ->label('Redis Password')
                    ->hint('The password for your redis server. Can be empty.')
                    ->password()
                    ->revealable()
                    ->default(config('database.redis.default.password')),
            ]);
    }
}
