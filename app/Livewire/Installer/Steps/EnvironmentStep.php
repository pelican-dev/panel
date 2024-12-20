<?php

namespace App\Livewire\Installer\Steps;

use App\Livewire\Installer\PanelInstaller;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

class EnvironmentStep
{
    public static function make(PanelInstaller $installer): Step
    {
        return Step::make('environment')
            ->label('Environment')
            ->columns()
            ->schema([
                TextInput::make('env_general.APP_NAME')
                    ->label('App Name')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('This will be the Name of your Panel.')
                    ->required()
                    ->default(config('app.name')),
                TextInput::make('env_general.APP_URL')
                    ->label('App URL')
                    ->hintIcon('tabler-question-mark')
                    ->hintIconTooltip('This will be the URL you access your Panel from.')
                    ->required()
                    ->default(url('')),
                Fieldset::make('adminuser')
                    ->label('Admin User')
                    ->columns(3)
                    ->schema([
                        TextInput::make('user.email')
                            ->label('E-Mail')
                            ->required()
                            ->email()
                            ->placeholder('admin@example.com'),
                        TextInput::make('user.username')
                            ->label('Username')
                            ->required()
                            ->placeholder('admin'),
                        TextInput::make('user.password')
                            ->label('Password')
                            ->required()
                            ->password()
                            ->revealable(),
                    ]),
            ])
            ->afterValidation(fn () => $installer->writeToEnv('env_general'));
    }
}
