<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

final class LinkedinSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'linkedin';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Obtain Linkedin App OAuth Config')
                ->schema([
                    TextEntry::make('create_application')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p><x-filament::link href="https://www.linkedin.com/developers/apps/new" target="_blank">Create</x-filament::link> or <x-filament::link href="https://www.linkedin.com/developers/apps" target="_blank">select</x-filament::link> the one you will be using for authentication.</p><p>Select the <b>Auth</b> tab and set <b>Authorized redirect URLs for your app</b> to the value below.</p>'))),
                    TextInput::make('_noenv_callback')
                        ->label('Authorized redirect URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/linkedin')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-linkedin-f';
    }

    public function getHexColor(): string
    {
        return '#0a66c2';
    }
}
