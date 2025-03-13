<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use SocialiteProviders\Steam\Provider;

final class SteamProvider extends OAuthProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    public function getId(): string
    {
        return 'steam';
    }

    public function getProviderClass(): string
    {
        return Provider::class;
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

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Create API Key')
                ->schema([
                    Placeholder::make('')
                        ->content(new HtmlString('Visit <u><a href="https://steamcommunity.com/dev/apikey" target="_blank">https://steamcommunity.com/dev/apikey</a></u> to generate an API key.')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-steam-f';
    }

    public function getHexColor(): string
    {
        return '#00adee';
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
