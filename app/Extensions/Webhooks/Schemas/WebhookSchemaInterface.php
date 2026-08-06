<?php

namespace App\Extensions\Webhooks\Schemas;

use App\Enums\WebhookScope;
use App\Models\WebhookConfiguration;
use BackedEnum;
use Filament\Schemas\Components\Component;

interface WebhookSchemaInterface
{
    /**
     * Unique identifier for this type, stored in the `type` column of a webhook configuration.
     */
    public function getId(): string;

    public function getLabel(): string;

    public function getColor(): ?string;

    public function getIcon(): string|BackedEnum|null;

    /**
     * Components shown in the payload section when this type is selected.
     *
     * @return Component[]
     */
    public function getFormComponents(WebhookScope $scope): array;

    /**
     * Livewire component rendered next to the payload form as a live preview,
     * or null when this type has nothing to preview.
     */
    public function getPreviewComponent(): ?string;

    /**
     * Top level form keys forwarded to the preview component whenever the form changes.
     * Keeping this list small avoids sending the entire form state on every keystroke.
     *
     * @return string[]
     */
    public function getPreviewFields(): array;

    /**
     * Whether this type should be selected automatically for the given endpoint.
     */
    public function matchesEndpoint(string $endpoint): bool;

    /**
     * Turn form state into model attributes, usually by collapsing the type specific
     * fields into `payload`.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateFormDataBeforeSave(array $data): array;

    /**
     * Turn model attributes back into form state, the inverse of mutateFormDataBeforeSave().
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function mutateFormDataBeforeFill(array $data): array;

    /**
     * Build the request body posted to the endpoint.
     *
     * @param  array<string, mixed>  $eventData
     * @return array<string, mixed>
     */
    public function preparePayload(WebhookConfiguration $webhookConfiguration, array $eventData): array;

    /**
     * Build the request headers posted to the endpoint.
     *
     * @param  array<string, mixed>  $eventData
     * @return array<string, string>
     */
    public function prepareHeaders(WebhookConfiguration $webhookConfiguration, array $eventData): array;
}
