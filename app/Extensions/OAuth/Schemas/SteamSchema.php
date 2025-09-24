<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use SocialiteProviders\Steam\Provider;

final class SteamSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'steam';
    }

    public function getSocialiteProvider(): string
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
                    TextEntry::make('create_api_key')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('Visit <x-filament::link href="https://steamcommunity.com/dev/apikey" target="_blank">https://steamcommunity.com/dev/apikey</x-filament::link> to generate an API key.'))),
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
}
