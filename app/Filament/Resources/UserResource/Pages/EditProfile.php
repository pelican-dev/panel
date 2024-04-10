<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\ActivityLog;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Tabs::make()->schema([
                            Tab::make('Account')
                                ->icon('tabler-user')
                                ->schema([
                                    TextInput::make('username')
                                        ->disabled()
                                        ->readOnly()
                                        ->maxLength(191)
                                        ->unique(ignoreRecord: true)
                                        ->autofocus(),

                                    TextInput::make('email')
                                        ->email()
                                        ->required()
                                        ->maxLength(191)
                                        ->unique(ignoreRecord: true),

                                    TextInput::make('password')
                                        ->password()
                                        ->revealable(filament()->arePasswordsRevealable())
                                        ->rule(Password::default())
                                        ->autocomplete('new-password')
                                        ->dehydrated(fn ($state): bool => filled($state))
                                        ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                                        ->live(debounce: 500)
                                        ->same('passwordConfirmation'),

                                    TextInput::make('passwordConfirmation')
                                        ->password()
                                        ->revealable(filament()->arePasswordsRevealable())
                                        ->required()
                                        ->visible(fn (Get $get): bool => filled($get('password')))
                                        ->dehydrated(false),
                                ]),

                            Tab::make('2FA')
                                ->icon('tabler-shield-lock')
                                ->schema([
                                    Placeholder::make('Coming soon!'),
                                ]),

                            Tab::make('API Keys')
                                ->icon('tabler-key')
                                ->schema([
                                    Placeholder::make('Coming soon!'),
                                ]),

                            Tab::make('SSH Keys')
                                ->icon('tabler-lock-code')
                                ->schema([
                                    Placeholder::make('Coming soon!'),
                                ]),

                            Tab::make('Activity')
                                ->icon('tabler-history')
                                ->schema([
                                    Repeater::make('activity')
                                        ->deletable(false)
                                        ->addable(false)
                                        ->relationship()

                                        ->schema([
                                            Placeholder::make('activity!')->label('')->content(fn (ActivityLog $log) => new HtmlString($log->htmlable())),
                                        ])
                                ]),
                        ]),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }
}
