<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Foundation\Application;
use SocialiteProviders\Authentik\Provider;

final class AuthentikProvider extends OAuthProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    public function getId(): string
    {
        return 'authentik';
    }

    public function getProviderClass(): string
    {
        return Provider::class;
    }

    public function getServiceConfig(): array
    {
        return [
            'base_url' => env('OAUTH_AUTHENTIK_BASE_URL'),
            'client_id' => env('OAUTH_AUTHENTIK_CLIENT_ID'),
            'client_secret' => env('OAUTH_AUTHENTIK_CLIENT_SECRET'),
        ];
    }

    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            TextInput::make('OAUTH_AUTHENTIK_BASE_URL')
                ->label('Base URL')
                ->placeholder('Base URL')
                ->columnSpan(2)
                ->required()
                ->url()
                ->autocomplete(false)
                ->default(env('OAUTH_AUTHENTIK_BASE_URL')),
            TextInput::make('OAUTH_AUTHENTIK_DISPLAY_NAME')
                ->label('Display Name')
                ->placeholder('Display Name')
                ->autocomplete(false)
                ->default(env('OAUTH_AUTHENTIK_DISPLAY_NAME', 'Authentik')),
            ColorPicker::make('OAUTH_AUTHENTIK_DISPLAY_COLOR')
                ->label('Display Color')
                ->placeholder('#fd4b2d')
                ->default(env('OAUTH_AUTHENTIK_DISPLAY_COLOR', '#fd4b2d'))
                ->hex(),
        ]);
    }

    public function getName(): string
    {
        return env('OAUTH_AUTHENTIK_DISPLAY_NAME', 'Authentik');
    }

    public function getHexColor(): string
    {
        return env('OAUTH_AUTHENTIK_DISPLAY_COLOR', '#fd4b2d');
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
