<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

final class FacebookSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'facebook';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new Facebook Application')
                ->schema([
                    TextEntry::make('create_application')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p>Visit the <x-filament::link href="https://developers.facebook.com/apps" target="_blank">Facebook Developer Dashboard</x-filament::link> and select or create a new app you will use for authentication. Make sure to have "Authenticate and request data from users with Facebook Login" as one of the Use Cases.</p><p>Once selected go to <b>Use Cases</b> and customize "Authenticate and request data from users with Facebook Login", from there go to <b>Settings</b> and add <b>Valid OAuth Redirect URIs</b> using the value below.</p>'))),
                    TextInput::make('_noenv_callback')
                        ->label('Valid OAuth Redirect URIs')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/facebook')),
                    TextEntry::make('get_app_info')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p>To obtain the OAuth values go to <b>App Settings > Basic</b>.</p>'))),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-facebook-f';
    }

    public function getHexColor(): string
    {
        return '#1877f2';
    }
}
