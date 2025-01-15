<?php

namespace App\Extensions\OAuth\Providers;

final class CommonProvider extends OAuthProvider
{
    protected function __construct(private string $id, private ?string $providerClass, private ?string $icon, private ?string $hexColor)
    {
        parent::__construct();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProviderClass(): ?string
    {
        return $this->providerClass;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getHexColor(): ?string
    {
        return $this->hexColor;
    }

    public static function register(string $id, ?string $providerClass = null, ?string $icon = null, ?string $hexColor = null): static
    {
        return new self($id, $providerClass, $icon, $hexColor);
    }
}
