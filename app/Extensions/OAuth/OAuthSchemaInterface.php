<?php

namespace App\Extensions\OAuth;

use App\Models\User;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Wizard\Step;
use Laravel\Socialite\Contracts\User as OAuthUser;

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

    public function shouldCreateMissingUser(OAuthUser $user): bool;

    public function shouldLinkMissingUser(User $user, OAuthUser $oauthUser): bool;
}
