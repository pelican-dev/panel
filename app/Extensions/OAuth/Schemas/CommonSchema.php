<?php

namespace App\Extensions\OAuth\Schemas;

final class CommonSchema extends OAuthSchema
{
    public function __construct(
        private readonly string $id,
        private readonly ?string $name = null,
        private readonly ?string $configName = null,
        private readonly ?string $icon = null,
        private readonly ?string $hexColor = null,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name ?? parent::getName();
    }

    public function getConfigKey(): string
    {
        return $this->configName ?? parent::getConfigKey();
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
