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
                            ->label('Captcha')
                            ->theme('auto'),
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
