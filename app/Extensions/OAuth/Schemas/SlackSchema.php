<?php

namespace App\Extensions\OAuth\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

final class SlackSchema extends OAuthSchema
{
    public function getId(): string
    {
        return 'slack';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new Slack OAuth')
                ->schema([
                    TextEntry::make('create_application')
                        ->hiddenLabel()
                        ->state(new HtmlString(Blade::render('<p><x-filament::link href="https://api.slack.com/apps?new_app=1" target="_blank">Create</x-filament::link> a slack app or <x-filament::link href="https://api.slack.com/apps" target="_blank">select</x-filament::link> the one you will be using for authentication.</p><p>Navigate to the <b>OAuth & Permissions</b> section and configure the <b>Redirect URL</b> using the value below.</p>'))),
                    TextInput::make('_noenv_callback')
                        ->label('Redirect URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintCopy()
                        ->default(fn () => url('/auth/oauth/callback/slack')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-slack';
    }

    public function getHexColor(): string
    {
        return '#6ecadc';
    }
}
