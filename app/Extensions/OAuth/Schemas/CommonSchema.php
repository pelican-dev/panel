<?php

namespace App\Extensions\OAuth\Schemas;

final class CommonSchema extends OAuthSchema
{
    public function __construct(private string $id, private ?string $providerClass, private ?string $icon, private ?string $hexColor)
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
}
