<?php

namespace App\Filament\Pages\Auth;

use Coderflex\FilamentTurnstile\Forms\Components\Turnstile;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
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

        parent::throwFailureValidationException();
    }
}
