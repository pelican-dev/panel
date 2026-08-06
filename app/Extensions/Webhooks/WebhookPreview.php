<?php

namespace App\Extensions\Webhooks;

use App\Enums\WebhookScope;
use App\Models\WebhookConfiguration;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Base class for the live preview rendered next to a webhook payload form.
 *
 * Extend this and implement render() to add a preview for a webhook type, then
 * return the component name from WebhookSchemaInterface::getPreviewComponent().
 */
abstract class WebhookPreview extends Component
{
    public ?WebhookConfiguration $record = null;

    /**
     * The live form state, limited to the keys the schema asked for.
     * Null until the form reports its first change.
     *
     * @var array<string, mixed>|null
     */
    public ?array $formPayload = null;

    #[On('webhook-form-changed')]
    public function onFormChanged(mixed $data = []): void
    {
        $this->formPayload = is_array($data) ? $data : [];
    }

    /**
     * The payload to render, with `{{variables}}` replaced by sample event data.
     * Prefers the live form state and falls back to what is stored on the record.
     *
     * @return array<string, mixed>
     */
    protected function resolvePayload(): array
    {
        $payload = $this->formPayload ?? $this->record?->payload;

        if (blank($payload)) {
            return [];
        }

        $configuration = $this->record ?? new WebhookConfiguration();

        $sampleData = $configuration->scope === WebhookScope::Server
            ? WebhookConfiguration::getServerWebhookSampleData()
            : WebhookConfiguration::getWebhookSampleData();

        $replaced = $configuration->replaceVars($sampleData, json_encode($payload) ?: '{}');

        return json_decode($replaced, true) ?? [];
    }
}
