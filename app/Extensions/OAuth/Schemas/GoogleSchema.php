<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

final class GoogleSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'google';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new OAuth client')
                ->schema([
                    TextEntry::make('create_application')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p>Visit the <x-filament::link href="https://console.developers.google.com/" target="_blank">Google API Console</x-filament::link> and create or select the project you want to use.</p><p>Navigate or search <b>Credentials</b>, click on the <b>Create Credentials</b> button and select <b>OAuth client ID</b>. On the Application type select <b>Web Application</b>.</p><p>On <b>Authorized JavaScript origins</b> and <b>Authorized redirect URIs</b> add and use the values below.</p>'))),
                    TextInput::make('_noenv_origin')
                        ->label('Authorized JavaScript origins')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('')),
                    TextInput::make('_noenv_callback')
                        ->label('Authorized redirect URIs')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/google')),
                    TextEntry::make('register_application')
                        ->hiddenLabel()
                        ->state(new HtmlString('<p>When you filled all fields click on <b>Create</b>.</p>')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-google-f';
    }

    public function getHexColor(): string
    {
        return '#4285f4';
    }
}
