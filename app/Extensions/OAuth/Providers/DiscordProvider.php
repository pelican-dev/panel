<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Foundation\Application;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use SocialiteProviders\Discord\Provider;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

final class DiscordProvider extends OAuthProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    public function getId(): string
    {
        return 'discord';
    }

    public function getProviderClass(): string
    {
        return Provider::class;
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new Discord OAuth App')
                ->schema([
                    Placeholder::make('')
                        ->content(new HtmlString('<p>Visit the <u><a href="https://discord.com/developers/applications" target="_blank">Discord Developer Portal</a></u> and click on <b>New Application</b>. Enter a <b>Name</b> (e.g. your panel name) and click on <b>Create</b>.</p><p>Copy the <b>Client ID</b> and the <b>Client Secret</b>, you will need them in the final step.</p>')),
                    Placeholder::make('')
                        ->content(new HtmlString('<p>Under <b>Redirects</b> add the below URL.</p>')),
                    TextInput::make('_noenv_callback')
                        ->label('Redirect URL')
                        ->dehydrated()
                        ->disabled()
                        ->hintAction(fn () => request()->isSecure() ? CopyAction::make() : null)
                        ->formatStateUsing(fn () => config('app.url') . (Str::endsWith(config('app.url'), '/') ? '' : '/') . 'auth/oauth/callback/discord'),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-discord-f';
    }

    public function getHexColor(): string
    {
        return '#5865F2';
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
