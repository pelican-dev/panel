<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use SocialiteProviders\Manager\SocialiteWasCalled;

abstract class OAuthProvider
{
    private static array $providers = [];

    public static function get(?string $id = null): array|self
    {
        return $id ? static::$providers[$id] : static::$providers;
    }

    protected function __construct()
    {
        config()->set('services.' . $this->getId(), array_merge($this->getServiceConfig(), ['redirect' => '/auth/oauth/callback/' . $this->getId()]));

        if ($this->getProviderClass()) {
            Event::listen(function (SocialiteWasCalled $event) {
                $event->extendSocialite($this->getId(), $this->getProviderClass());
            });
        }

        static::$providers[$this->getId()] = $this;
    }

    abstract public function getId(): string;

    abstract public function getProviderClass(): ?string;

    public function getServiceConfig(): array
    {
        $id = Str::upper($this->getId());

        return [
            'client_id' => env("OAUTH_{$id}_CLIENT_ID"),
            'client_secret' => env("OAUTH_{$id}_CLIENT_SECRET"),
        ];
    }

    public function getSettingsForm(): array
    {
        $id = Str::upper($this->getId());

        return [
            TextInput::make("OAUTH_{$id}_CLIENT_ID")
                ->label('Client ID')
                ->placeholder('Client ID')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("OAUTH_{$id}_CLIENT_ID")),
            TextInput::make("OAUTH_{$id}_CLIENT_SECRET")
                ->label('Client Secret')
                ->placeholder('Client Secret')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("OAUTH_{$id}_CLIENT_SECRET")),
        ];
    }

    public function getSetupForm(): array
    {
        return $this->getSettingsForm();
    }

    public function getName(): string
    {
        return Str::title($this->getId());
    }

    public function getIcon(): ?string
    {
        return null;
    }

    public function getHexColor(): ?string
    {
        return null;
    }

    public function isEnabled(): bool
    {
        $id = Str::upper($this->getId());

        return env("OAUTH_{$id}_ENABLED");
    }
}
