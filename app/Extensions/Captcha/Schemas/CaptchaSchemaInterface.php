<?php

namespace App\Extensions\Captcha\Schemas;

use Filament\Forms\Components\Component;

interface CaptchaSchemaInterface
{
    public function getId(): string;

    public function getName(): string;

    /**
     * @return array<string, string|string[]|bool|null>
     */
    public function getConfig(): array;

    public function isEnabled(): bool;

    public function getFormComponent(): Component;

    /**
     * @return Component[]
     */
    public function getSettingsForm(): array;

    public function getIcon(): ?string;

    /**
     * @return array<string, string|bool>
     */
    public function validateResponse(?string $captchaResponse = null): array;

    public function verifyDomain(string $hostname, ?string $requestUrl = null): bool;
}
