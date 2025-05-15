<?php

namespace App\Extensions\OAuth\Schemas;

final class CommonSchema extends OAuthSchema
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $icon,
        private readonly ?string $hexColor
    ) {}

    public function getId(): string
    {
        return $this->id;
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
