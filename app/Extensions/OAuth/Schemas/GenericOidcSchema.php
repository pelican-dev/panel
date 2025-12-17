<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Kovah\LaravelSocialiteOidc\OidcProvider;

final class GenericOidcSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'oidc';
    }

    public function getSocialiteProvider(): string
    {
        return OidcProvider::class;
    }

    public function getServiceConfig(): array
    {
        $config = array_merge(parent::getServiceConfig(), [
            'base_url' => env('OAUTH_OIDC_BASE_URL'),
        ]);

        $realm = env('OAUTH_OIDC_REALM');
        if ($realm) {
            $config['realm'] = $realm;
        }

        return $config;
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Configure OIDC Provider')
                ->schema([
                    TextEntry::make('setup_instructions')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p>Configure your OIDC provider (e.g., Keycloak, Auth0, Okta) with the following settings:</p><ul><li>Create an OAuth2/OpenID Connect application</li><li>Set the <b>Redirect URI</b> to the value below</li><li>Copy the <b>Client ID</b> and <b>Client Secret</b> for use in the configuration step</li></ul>'))),
                    TextInput::make('_noenv_callback')
                        ->label('Callback URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/oidc')),
                    TextEntry::make('keycloak_note')
                        ->hiddenLabel()
                        ->state(new HtmlString('<p><b>For Keycloak:</b> The Base URL should point to your realm (e.g., <code>https://keycloak.example.com/realms/my-realm</code>). Optionally, you can specify the realm name separately.</p>')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            TextInput::make('OAUTH_OIDC_BASE_URL')
                ->label('Base URL')
                ->placeholder('https://your-oidc-provider.com')
                ->columnSpan(2)
                ->required()
                ->url()
                ->autocomplete(false)
                ->helperText('The base URL of your OIDC provider. For Keycloak, include the realm path (e.g., https://keycloak.example.com/realms/my-realm)')
                ->default(env('OAUTH_OIDC_BASE_URL')),
            TextInput::make('OAUTH_OIDC_REALM')
                ->label('Realm')
                ->placeholder('Realm (optional, for Keycloak)')
                ->columnSpan(2)
                ->autocomplete(false)
                ->helperText('Optional: Specify the realm name if not included in the Base URL')
                ->default(env('OAUTH_OIDC_REALM')),
            TextInput::make('OAUTH_OIDC_DISPLAY_NAME')
                ->label('Display Name')
                ->placeholder('OIDC')
                ->columnSpan(2)
                ->autocomplete(false)
                ->default(env('OAUTH_OIDC_DISPLAY_NAME', 'OIDC')),
            ColorPicker::make('OAUTH_OIDC_DISPLAY_COLOR')
                ->label('Display Color')
                ->placeholder('#0066cc')
                ->default(env('OAUTH_OIDC_DISPLAY_COLOR', '#0066cc'))
                ->hex(),
        ]);
    }

    public function getName(): string
    {
        return env('OAUTH_OIDC_DISPLAY_NAME', 'OIDC');
    }

    public function getHexColor(): string
    {
        return env('OAUTH_OIDC_DISPLAY_COLOR', '#0066cc');
    }

    public function getIcon(): string
    {
        return 'tabler-key';
    }
}
