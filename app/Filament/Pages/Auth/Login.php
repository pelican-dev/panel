<?php

namespace App\Filament\Pages\Auth;

use App\Extensions\Captcha\Providers\CaptchaProvider;
use App\Extensions\OAuth\Providers\OAuthProvider;
use App\Models\User;
use Filament\Auth\Http\Responses\LoginResponse;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Sleep;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class Login extends \Filament\Auth\Pages\Login
{
    private Google2FA $google2FA;

    public bool $verifyTwoFactor = false;

    public function boot(Google2FA $google2FA): void
    {
        $this->google2FA = $google2FA;
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        Filament::auth()->once($this->getCredentialsFromFormData($data));

        /** @var ?User $user */
        $user = Filament::auth()->user();

        // Make sure that rate limits apply
        if (!$user) {
            return parent::authenticate();
        }

        // 2FA disabled
        if (!$user->use_totp) {
            return parent::authenticate();
        }

        $token = $data['2fa'] ?? null;

        // 2FA not shown yet
        if ($token === null) {
            $this->verifyTwoFactor = true;

            return null;
        }

        $isValidToken = $this->google2FA->verifyKey(
            $user->totp_secret,
            $token,
            Config::integer('panel.auth.2fa.window'),
        );

        if (!$isValidToken) {
            // Buffer to prevent bruteforce
            Sleep::sleep(1);

            Notification::make()
                ->title(trans('auth.failed-two-factor'))
                ->body(trans('auth.failed'))
                ->color('danger')
                ->icon('tabler-auth-2fa')
                ->danger()
                ->send();

            return null;
        }

        return parent::authenticate();
    }

    public function form(Schema $schema): Schema
    {
        $components = [
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
            $this->getOAuthFormComponent(),
            $this->getTwoFactorAuthenticationComponent(),
        ];

        if ($captchaProvider = $this->getCaptchaComponent()) {
            $components = array_merge($components, [$captchaProvider]);
        }

        return $schema
            ->components($components);
    }

    private function getTwoFactorAuthenticationComponent(): Component
    {
        return TextInput::make('2fa')
            ->label(trans('auth.two-factor-code'))
            ->hidden(fn () => !$this->verifyTwoFactor)
            ->required()
            ->live();
    }

    private function getCaptchaComponent(): ?Component
    {
        $captchaProvider = collect(CaptchaProvider::get())->filter(fn (CaptchaProvider $provider) => $provider->isEnabled())->first();

        if (!$captchaProvider) {
            return null;
        }

        return $captchaProvider->getComponent();
    }

    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');

        throw ValidationException::withMessages([
            'data.login' => trans('filament-panels::pages/auth/login.messages.failed'),
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

        $oauthProviders = collect(OAuthProvider::get())->filter(fn (OAuthProvider $provider) => $provider->isEnabled())->all();

        foreach ($oauthProviders as $oauthProvider) {

            $id = $oauthProvider->getId();

            $actions[] = Action::make("oauth_$id")
                ->label($oauthProvider->getName())
                ->icon($oauthProvider->getIcon())
                //TODO ->color(Color::hex($oauthProvider->getHexColor()))
                ->url(route('auth.oauth.redirect', ['driver' => $id], false));
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
