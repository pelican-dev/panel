<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Foundation\Application;
use SocialiteProviders\Authelia\Provider;

final class AutheliaProvider extends OAuthProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    public function getId(): string
    {
        return 'authelia';
    }

    public function getProviderClass(): string
    {
        return Provider::class;
    }

    public function getServiceConfig(): array
    {
        return [
            'base_url' => env('OAUTH_AUTHELIA_BASE_URL'),
            'client_id' => env('OAUTH_AUTHELIA_CLIENT_ID'),
            'client_secret' => env('OAUTH_AUTHELIA_CLIENT_SECRET'),
        ];
    }

    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            TextInput::make('OAUTH_AUTHELIA_BASE_URL')
                ->label('Base URL')
                ->placeholder('Base URL')
                ->columnSpan(2)
                ->required()
                ->url()
                ->autocomplete(false)
                ->default(env('OAUTH_AUTHELIA_BASE_URL')),
            TextInput::make('OAUTH_AUTHELIA_DISPLAY_NAME')
                ->label('Display Name')
                ->placeholder('Display Name')
                ->autocomplete(false)
                ->default(env('OAUTH_AUTHELIA_DISPLAY_NAME', 'Authelia')),
            ColorPicker::make('OAUTH_AUTHELIA_DISPLAY_COLOR')
                ->label('Display Color')
                ->placeholder('#b2c6fe')
                ->default(env('OAUTH_AUTHELIA_DISPLAY_COLOR', '#b2c6fe'))
                ->hex(),
        ]);
    }

    public function getName(): string
    {
        return env('OAUTH_AUTHELIA_DISPLAY_NAME', 'Authelia');
    }

    public function getHexColor(): string
    {
        return env('OAUTH_AUTHELIA_DISPLAY_COLOR', '#b2c6fe');
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
