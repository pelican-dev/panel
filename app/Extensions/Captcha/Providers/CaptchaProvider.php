<?php

namespace App\Extensions\Captcha\Providers;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

abstract class CaptchaProvider
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
                logger()->warning("Tried to create duplicate Captcha provider with id '{$this->getId()}'");
            }

            return;
        }

        config()->set('captcha.' . Str::lower($this->getId()), $this->getConfig());

        static::$providers[$this->getId()] = $this;
    }

    abstract public function getId(): string;

    abstract public function getComponent(): Component;

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getConfig(): array
    {
        $id = Str::upper($this->getId());

        return [
            'site_key' => env("CAPTCHA_{$id}_SITE_KEY"),
            'secret_key' => env("CAPTCHA_{$id}_SECRET_KEY"),
        ];
    }

    /**
     * @return Component[]
     */
    public function getSettingsForm(): array
    {
        $id = Str::upper($this->getId());

        return [
            TextInput::make("CAPTCHA_{$id}_SITE_KEY")
                ->label('Site Key')
                ->placeholder('Site Key')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("CAPTCHA_{$id}_SITE_KEY")),
            TextInput::make("CAPTCHA_{$id}_SECRET_KEY")
                ->label('Secret Key')
                ->placeholder('Secret Key')
                ->columnSpan(2)
                ->required()
                ->password()
                ->revealable()
                ->autocomplete(false)
                ->default(env("CAPTCHA_{$id}_SECRET_KEY")),
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

    public function isEnabled(): bool
    {
        $id = Str::upper($this->getId());

        return env("CAPTCHA_{$id}_ENABLED", false);
    }

    /**
     * @return array<string, string|bool>
     */
    public function validateResponse(?string $captchaResponse = null): array
    {
        return [
            'success' => false,
            'message' => 'validateResponse not defined',
        ];
    }

    public function verifyDomain(string $hostname, ?string $requestUrl = null): bool
    {
        return true;
    }
}
