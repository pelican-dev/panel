<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\TextInput;
use Illuminate\Foundation\Application;

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
        // TODO
        return parent::getSetupSteps();
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
