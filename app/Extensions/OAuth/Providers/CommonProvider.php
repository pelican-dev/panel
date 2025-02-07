<?php

namespace App\Extensions\OAuth\Providers;

final class CommonProvider extends OAuthProvider
{
    protected function __construct(private string $id, private ?string $icon, private ?string $hexColor)
    {
        parent::__construct();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProviderClass(): null
    {
        return null;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getHexColor(): ?string
    {
        return $this->hexColor;
    }

    public static function register(string $id, ?string $icon = null, ?string $hexColor = null): static
    {
        return new self($id, $icon, $hexColor);
    }
}
