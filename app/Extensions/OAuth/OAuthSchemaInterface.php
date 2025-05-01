<?php

namespace App\Extensions\OAuth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Wizard\Step;

interface OAuthSchemaInterface
{
    public function getId(): string;

    /** @return ?class-string */
    public function getProviderClass(): ?string;

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getServiceConfig(): array;

    /** @return Component[] */
    public function getSettingsForm(): array;

    /** @return Step[] */
    public function getSetupSteps(): array;

    public function getName(): string;

    public function getIcon(): ?string;

    public function getHexColor(): ?string;

    public function isEnabled(): bool;
}
