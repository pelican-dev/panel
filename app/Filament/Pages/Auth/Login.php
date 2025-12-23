<?php

namespace App\Filament\Pages\Auth;

use App\Extensions\Captcha\CaptchaService;
use App\Extensions\OAuth\OAuthService;
use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected OAuthService $oauthService;

    protected CaptchaService $captchaService;

    public function boot(OAuthService $oauthService, CaptchaService $captchaService): void
    {
        $this->oauthService = $oauthService;
        $this->captchaService = $captchaService;
    }

    public function form(Schema $schema): Schema
    {
        $components = [
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
            $this->getOAuthFormComponent(),
        ];

        if ($captchaComponent = $this->getCaptchaComponent()) {
            $components[] = $captchaComponent
                ->hidden(fn () => filled($this->userUndertakingMultiFactorAuthentication));
        }

        return $schema
            ->components($components);
    }

    private function getCaptchaComponent(): ?Component
    {
        return $this->captchaService->getActiveSchema()?->getFormComponent();
    }

    protected function throwFailureValidationException(): never
    {
        $this->dispatch('reset-captcha');

        throw ValidationException::withMessages([
            'data.login' => trans('filament-panels::auth/pages/login.messages.failed')]);
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(trans('filament-panels::auth/pages/login.title'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getOAuthFormComponent(): Component
    {
        $actions = [];

        $oauthSchemas = $this->oauthService->getEnabled();

        foreach ($oauthSchemas as $schema) {

            $id = $schema->getId();

            $color = $schema->getHexColor();
            $color = is_string($color) ? Color::hex($color) : null;

            $actions[] = Action::make("oauth_$id")
                ->label($schema->getName())
                ->icon($schema->getIcon())
                ->color($color)
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
