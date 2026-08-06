<?php

namespace App\Extensions\Webhooks\Schemas;

use App\Enums\TablerIcon;
use App\Enums\WebhookScope;
use App\Models\WebhookConfiguration;
use BackedEnum;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Str;

abstract class BaseSchema implements WebhookSchemaInterface
{
    abstract public function getId(): string;

    public function getLabel(): string
    {
        return Str::headline($this->getId());
    }

    public function getColor(): ?string
    {
        return null;
    }

    public function getIcon(): string|BackedEnum|null
    {
        return TablerIcon::Webhook;
    }

    /** @return Component[] */
    public function getFormComponents(WebhookScope $scope): array
    {
        return [];
    }

    public function getPreviewComponent(): ?string
    {
        return null;
    }

    /** @return string[] */
    public function getPreviewFields(): array
    {
        return [];
    }

    public function matchesEndpoint(string $endpoint): bool
    {
        return false;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $eventData
     * @return array<string, mixed>
     */
    public function preparePayload(WebhookConfiguration $webhookConfiguration, array $eventData): array
    {
        return $eventData;
    }

    /**
     * @param  array<string, mixed>  $eventData
     * @return array<string, string>
     */
    public function prepareHeaders(WebhookConfiguration $webhookConfiguration, array $eventData): array
    {
        return [];
    }
}
