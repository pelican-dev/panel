<?php

namespace App\Extensions\OAuth\Providers;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use SocialiteProviders\Manager\SocialiteWasCalled;

abstract class OAuthProvider
{
    /**
     * @var array<string, static>
     */
    protected static array $providers = [];

    /**
     * @return self|static[]
     */
    public static function get(?string $id = null): array|self
    {
        return $id ? static::$providers[$id] : static::$providers;
    }

    protected function __construct(protected Application $app)
    {
        if (array_key_exists($this->getId(), static::$providers)) {
            if (!$this->app->runningUnitTests()) {
                logger()->warning("Tried to create duplicate OAuth provider with id '{$this->getId()}'");
            }

            return;
        }

        config()->set('services.' . $this->getId(), array_merge($this->getServiceConfig(), ['redirect' => '/auth/oauth/callback/' . $this->getId()]));

        if ($this->getProviderClass()) {
            Event::listen(function (SocialiteWasCalled $event) {
                $event->extendSocialite($this->getId(), $this->getProviderClass());
            });
        }

        static::$providers[$this->getId()] = $this;
    }

    abstract public function getId(): string;

    public function getProviderClass(): ?string
    {
        return null;
    }

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getServiceConfig(): array
    {
        $id = Str::upper($this->getId());

        return [
            'client_id' => env("OAUTH_{$id}_CLIENT_ID"),
            'client_secret' => env("OAUTH_{$id}_CLIENT_SECRET"),
        ];
    }

    /**
     * @return Component[]
     */
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

    /**
     * @return Step[]
     */
    public function getSetupSteps(): array
    {
        return [
            Step::make('OAuth Config')
                ->columns(4)
                ->schema($this->getSettingsForm()),
        ];
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

        return env("OAUTH_{$id}_ENABLED", false);
    }
}
