<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

final class GithubProvider extends OAuthProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    public function getId(): string
    {
        return 'github';
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            \Filament\Schemas\Components\Wizard\Step::make('Register new Github OAuth App')
                ->schema([
                    Placeholder::make('INeedAName2')
                        ->content(new HtmlString(Blade::render('<p>Visit the <x-filament::link href="https://github.com/settings/developers" target="_blank">Github Developer Dashboard</x-filament::link>, go to <b>OAuth Apps</b> and click on <b>New OAuth App</b>.</p><p>Enter an <b>Application name</b> (e.g. your panel name), set <b>Homepage URL</b> to your panel url and enter the below url as <b>Authorization callback URL</b>.</p>'))),
                    TextInput::make('_noenv_callback')
                        ->label('Authorization callback URL')
                        ->dehydrated()
                        ->disabled()
                        //TODO ->hintAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                        ->default(fn () => url('/auth/oauth/callback/github')),
                    Placeholder::make('INeedAName')
                        ->content(new HtmlString('<p>When you filled all fields click on <b>Register application</b>.</p>')),
                ]),
            \Filament\Schemas\Components\Wizard\Step::make('Create Client Secret')
                ->schema([
                    Placeholder::make('INeedAName3')
                        ->content(new HtmlString('<p>Once you registered your app, generate a new <b>Client Secret</b>.</p><p>You will also need the <b>Client ID</b>.</p>')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-github-f';
    }

    public function getHexColor(): string
    {
        return '#4078c0';
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
