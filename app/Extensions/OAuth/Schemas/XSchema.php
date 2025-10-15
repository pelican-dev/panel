<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

final class XSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'x';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new X App')
                ->schema([
                    TextEntry::make('create_application')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p>Visit the <x-filament::link href="https://developer.x.com/en/portal/dashboard" target="_blank">X Developer Dashboard</x-filament::link> and create or select the project app you want to use.</p><p>Go to the app\'s settings and set up <b>User authentication</b> if not yet. Make sure to select <b>Web App</b> as the type of app.</p><p>For the <b>Callback URI / Redirect URL</b> and <b>Website URL</b> set it using the value below.</p>'))),
                    TextInput::make('_noenv_origin')
                        ->label('Website URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('')),
                    TextInput::make('_noenv_callback')
                        ->label('Callback URI / Redirect URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/x')),
                    TextEntry::make('register_application')
                        ->hiddenLabel()
                        ->state(new HtmlString('<p>If you have already set this up go to your app\'s <b>Keys and tokens</b> and obtain the Client ID and Secret there.</p>')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-x';
    }

    public function getHexColor(): string
    {
        return '#1da1f2';
    }
}
