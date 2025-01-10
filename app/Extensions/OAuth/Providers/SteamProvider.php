<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\TextInput;

final class SteamProvider extends OAuthProvider
{
    public function getId(): string
    {
        return 'steam';
    }

    public function getProviderClass(): string
    {
        return \SocialiteProviders\Steam\Provider::class;
    }

    public function getServiceConfig(): array
    {
        return [
            'client_id' => null,
            'client_secret' => env('OAUTH_STEAM_CLIENT_SECRET'),
            'allowed_hosts' => [
                str_replace(['http://', 'https://'], '', env('APP_URL')),
            ],
        ];
    }

    public function getSettingsForm(): array
    {
        return [
            TextInput::make('OAUTH_STEAM_CLIENT_SECRET')
                ->label('Web API Key')
                ->placeholder('Web API Key')
                ->columnSpan(4)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env('OAUTH_STEAM_CLIENT_SECRET')),
        ];
    }

    public function getIcon(): ?string
    {
        return 'tabler-brand-steam-f';
    }

    public function getHexColor(): ?string
    {
        return '#00adee';
    }

    public static function register(): self
    {
        return new self();
    }
}
