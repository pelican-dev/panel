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
                    ->hintIconTooltip('The name of your redis user.')
                    ->required()
                    ->default(config('database.redis.default.username')),
                TextInput::make('env.REDIS_PASSWORD')
                    ->label('Redis Password')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('The password for your redis user. Can be empty.')
                    ->password()
                    ->revealable()
                    ->default(config('database.redis.default.password')),
            ]);
    }
}
