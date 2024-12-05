<?php

namespace App\Filament\Pages\Auth;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getLoginFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        $this->getOAuthFormComponent(),
                        Turnstile::make('captcha')
                            ->hidden(!config('turnstile.turnstile_enabled'))
                            ->validationMessages([
                                'required' => config('turnstile.error_messages.turnstile_check_message'),
                            ]),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');

        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('Login')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getOAuthFormComponent(): Component
    {
        $actions = [];

        foreach (config('auth.oauth') as $name => $data) {
            if (!$data['enabled']) {
                continue;
            }

            $actions[] = Action::make("oauth_$name")
                ->label(Str::title($name))
                ->icon($data['icon'])
                ->color($data['color'])
                ->url(route('auth.oauth.redirect', ['driver' => $name], false));
        }

        return Actions::make($actions);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $loginType = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $loginType => mb_strtolower($data['login']),
            'password' => $data['password'],
        ];
    }
}
