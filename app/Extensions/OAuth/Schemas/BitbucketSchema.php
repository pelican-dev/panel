<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

final class BitbucketSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'bitbucket';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new Bitbucket Consumer')
                ->schema([
                    TextEntry::make('create_application')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p>Visit the <x-filament::link href="https://support.atlassian.com/bitbucket-cloud/docs/use-oauth-on-bitbucket-cloud" target="_blank">Bitbucket OAuth Documentation</x-filament::link> and follow the steps in <b>Create a consumer</b>.</p><p>For the <b>Callback URL</b> use the value below.</p>'))),
                    TextInput::make('_noenv_callback')
                        ->label('Callback URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/bitbucket')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-bitbucket-f';
    }

    public function getHexColor(): string
    {
        return '#205081';
    }
}
