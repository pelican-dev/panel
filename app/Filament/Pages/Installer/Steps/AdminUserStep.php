<?php

namespace App\Filament\Pages\Installer\Steps;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

class AdminUserStep
{
    public static function make(): Step
    {
        return Step::make('user')
            ->label('Admin User')
            ->schema([
                TextInput::make('user.email')
                    ->label('Admin E-Mail')
                    ->required()
                    ->email()
                    ->default('admin@example.com'),
                TextInput::make('user.username')
                    ->label('Admin Username')
                    ->required()
                    ->default('admin'),
                TextInput::make('user.password')
                    ->label('Admin Password')
                    ->required()
                    ->password()
                    ->revealable(),
            ]);
    }
}
