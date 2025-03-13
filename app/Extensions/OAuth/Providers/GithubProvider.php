<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
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
            Step::make('Register new Github OAuth App')
                ->schema([
                    Placeholder::make('')
                        ->content(new HtmlString('<p>Visit the <u><a href="https://github.com/settings/developers" target="_blank">Github Developer Dashboard</a></u>, go to <b>OAuth Apps</b> and click on <b>New OAuth App</b>.</p><p>Enter an <b>Application name</b> (e.g. your panel name), set <b>Homepage URL</b> to your panel url and enter the below url as <b>Authorization callback URL</b>.</p>')),
                    TextInput::make('_noenv_callback')
                        ->label('Authorization callback URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                        ->default(fn () => config('app.url') . (Str::endsWith(config('app.url'), '/') ? '' : '/') . 'auth/oauth/callback/github'),
                    Placeholder::make('')
                        ->content(new HtmlString('<p>When you filled all fields click on <b>Register application</b>.</p>')),
                ]),
            Step::make('Create Client Secret')
                ->schema([
                    Placeholder::make('')
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
