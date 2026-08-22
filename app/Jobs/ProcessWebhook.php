<?php

namespace App\Jobs;

use App\Extensions\Webhooks\WebhookTypeService;
use App\Models\WebhookConfiguration;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<mixed>  $data
     */
    public function __construct(
        private WebhookConfiguration $webhookConfiguration,
        private string $eventName,
        private array $data
    ) {}

    public function handle(WebhookTypeService $webhookTypeService): void
    {
        $data = $this->data[0] ?? [];
        if (count($data) === 1) {
            $data = reset($data);
        }

        $data = $this->normalizeData($data);
        $data['event'] = $this->webhookConfiguration->transformClassName($this->eventName);

        $schema = $webhookTypeService->get($this->webhookConfiguration->type);

        if ($schema) {
            $payload = $schema->preparePayload($this->webhookConfiguration, $data);
            $headers = $schema->prepareHeaders($this->webhookConfiguration, $payload, $data);
        } else {
            $payload = $this->replaceStoredPayloadVars($data);
            $headers = [];
        }

        $retryAfter = null;

        try {
            // The type owns the request, so it is free to change the verb, encoding or timeout
            $response = $schema
                ? $schema->deliver($this->webhookConfiguration, $payload, $headers)
                : Http::withHeaders($headers)->post($this->webhookConfiguration->endpoint, $payload);

            $successful = ($schema?->wasSuccessful($response) ?? $response->successful()) ? now() : null;

            if (!$successful) {
                report(sprintf(
                    'Webhook #%d delivery to %s failed with status %d.',
                    $this->webhookConfiguration->id,
                    $this->redactedEndpoint(),
                    $response->status(),
                ));

                $retryAfter = $schema?->retryAfter($response);
            }
        } catch (Exception $exception) {
            report($exception->getMessage());
            $successful = null;
        }

        $this->webhookConfiguration->webhooks()->create([
            'payload' => $payload,
            'successful_at' => $successful,
            'event' => $this->eventName,
            'endpoint' => $this->webhookConfiguration->endpoint,
        ]);

        // Only types that ask for it are retried, so default behaviour is unchanged
        if ($retryAfter !== null) {
            $this->release($retryAfter);
        }
    }

    /**
     * Webhook URLs routinely embed a secret, a Discord webhook token for example, so
     * only the host is ever written to the logs.
     */
    private function redactedEndpoint(): string
    {
        return parse_url($this->webhookConfiguration->endpoint, PHP_URL_HOST) ?: 'unknown host';
    }

    /**
     * @param  array<mixed>  $data
     * @return array<mixed>
     */
    private function replaceStoredPayloadVars(array $data): array
    {
        if (blank($this->webhookConfiguration->payload)) {
            return $data;
        }

        $payload = json_encode($this->webhookConfiguration->payload);
        if ($payload === false) {
            return $data;
        }

        return json_decode($this->webhookConfiguration->replaceVars($data, $payload), true) ?? $data;
    }

    /** @return array<mixed> */
    private function normalizeData(mixed $data): array
    {
        if (is_string($data)) {
            return Arr::wrap(json_decode($data, true) ?? []);
        }

        if (is_object($data)) {
            return Arr::wrap($data->toArray());
        }

        return Arr::wrap($data);
    }
}
