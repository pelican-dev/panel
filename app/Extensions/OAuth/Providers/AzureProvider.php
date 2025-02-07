<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use SocialiteProviders\Azure\Provider;

final class AzureProvider extends OAuthProvider
{
    public function getId(): string
    {
        return 'azure';
    }

    public function getProviderClass(): string
    {
        return Provider::class;
    }

    public function getServiceConfig(): array
    {
        return [
            'client_id' => env('OAUTH_AZURE_CLIENT_ID'),
            'client_secret' => env('OAUTH_AZURE_CLIENT_SECRET'),
            'tenant' => env('OAUTH_AZURE_TENANT_ID'),
        ];
    }
    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            TextInput::make('OAUTH_AZURE_DISPLAY_NAME')
                ->label('Display Name')
                ->placeholder('Display Name')
                ->autocomplete(false)
                ->default(env('OAUTH_AZURE_DISPLAY_NAME', 'Azure')),
            TextInput::make("OAUTH_AZURE_TENANT_ID")
                ->label('Tenant ID')
                ->placeholder('Tenant ID')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("OAUTH_AZURE_TENANT_ID")),
        ]);
    }

    public function getName(): string
    {
        return env('OAUTH_AZURE_DISPLAY_NAME') ?? 'Azure';
    }

    public function getIcon(): string
    {
        return 'tabler-brand-azure';
    }

    public function getHexColor(): string
    {
        return '#03c6fc';
    }

    public static function register(): self
    {
        return new self();
    }
}
