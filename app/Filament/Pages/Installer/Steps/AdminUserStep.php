<?php

namespace App\Filament\Pages\Installer\Steps;

use App\Filament\Pages\Installer\PanelInstaller;
use App\Services\Users\UserCreationService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

class AdminUserStep
{
    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('user')
            ->label('Admin User')
            ->schema([
                TextInput::make('user.email')
                    ->label('Admin E-Mail')
                    ->required()
                    ->email()
                    ->placeholder('admin@example.com'),
                TextInput::make('user.username')
                    ->label('Admin Username')
                    ->required()
                    ->placeholder('admin'),
                TextInput::make('user.password')
                    ->label('Admin Password')
                    ->required()
                    ->password()
                    ->revealable(),
            ])
            ->afterValidation(fn (UserCreationService $service) => $installer->createAdminUser($service));
    }
}
