<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

final class GitlabProvider extends OAuthProvider
{
    public function __construct(protected Application $app)
    {
        parent::__construct($app);
    }

    public function getId(): string
    {
        return 'gitlab';
    }

    public function getServiceConfig(): array
    {
        return array_merge(parent::getServiceConfig(), [
            'host' => env('OAUTH_GITLAB_HOST'),
        ]);
    }

    public function getSettingsForm(): array
    {
        return array_merge(parent::getSettingsForm(), [
            TextInput::make('OAUTH_GITLAB_HOST')
                ->label('Custom Host')
                ->placeholder('Only set a custom host if you are self hosting gitlab')
                ->columnSpan(2)
                ->url()
                ->autocomplete(false)
                ->default(env('OAUTH_GITLAB_HOST')),
        ]);
    }

    public function getSetupSteps(): array
    {
        return array_merge([
            Step::make('Register new Gitlab OAuth App')
                ->schema([
                    Placeholder::make('')
                        ->content(new HtmlString(Blade::render('Check out the <x-filament::link href="https://docs.gitlab.com/integration/oauth_provider/" target="_blank">Gitlab docs</x-filament::link> on how to create the oauth app.'))),
                    TextInput::make('_noenv_callback')
                        ->label('Redirect URI')
                        ->dehydrated()
                        ->disabled()
                        ->hintAction(fn (string $state) => request()->isSecure() ? CopyAction::make()->copyable($state) : null)
                        ->default(fn () => url('/auth/oauth/callback/gitlab')),
                ]),
        ], parent::getSetupSteps());
    }

    public function getIcon(): string
    {
        return 'tabler-brand-gitlab';
    }

    public function getHexColor(): string
    {
        return '#fca326';
    }

    public static function register(Application $app): self
    {
        return new self($app);
    }
}
