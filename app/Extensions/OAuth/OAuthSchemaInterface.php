<?php

namespace App\Extensions\OAuth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\Wizard\Step;

interface OAuthSchemaInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getConfigKey(): string;

    /** @return ?class-string */
    public function getSocialiteProvider(): ?string;

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getServiceConfig(): array;

    /** @return Component[] */
    public function getSettingsForm(): array;

    /** @return Step[] */
    public function getSetupSteps(): array;

    public function getIcon(): ?string;

    public function getHexColor(): ?string;

    public function isEnabled(): bool;

    public function shouldCreateMissingUsers(): bool;

    public function shouldLinkMissingUsers(): bool;
}
