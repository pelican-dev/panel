<?php

namespace App\Extensions\Captcha\Schemas;

use BackedEnum;
use Filament\Schemas\Components\Component;

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

    public function getIcon(): null|string|BackedEnum;

    public function validateResponse(?string $captchaResponse = null): void;
}
