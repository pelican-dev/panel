<?php

namespace App\Jobs;

use App\Models\WebhookConfiguration;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use App\Enums\WebhookType;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<mixed>  $data
     */
    public function __construct(
        private int $webhookConfigurationId,
        private string $eventName,
        private array $data
    ) {}

    public function handle(): void
    {
        $webhookConfiguration = WebhookConfiguration::findOrFail($this->webhookConfigurationId);
        $data = $this->data[0];

        if ($webhookConfiguration->type === WebhookType::Discord) {
            $data = array_merge(
                json_decode($data, true),
                ['event' => $webhookConfiguration->transformClassName($this->eventName)]
            );

            $payload = json_encode($webhookConfiguration->payload);
            $tmp = $webhookConfiguration->replaceVars($data, $payload);
            $data = json_decode($tmp, true);

            $embeds = data_get($data, 'embeds');
            if ($embeds) {
                foreach ($embeds as &$embed) {
                    if (data_get($embed, 'has_timestamp')) {
                        $embed['timestamp'] = Carbon::now();
                        unset($embed['has_timestamp']);
                    }
                }
                $data['embeds'] = $embeds;
            }
        }

        if (isset($data['headers'])) {
            unset($data['headers']);
        }

        try {
            $headers = [
                'X-Webhook-Event' => $this->eventName,
            ];

            if (
                $webhookConfiguration->type === WebhookType::Standalone
                && !empty($webhookConfiguration->headers)
            ) {
                $decodedHeaders = json_decode($webhookConfiguration->headers, true) ?? [];
                foreach ($decodedHeaders as $key => $value) {
                    $headers[$key] = $value;
                }
            }

            Http::withHeaders($headers)
                ->post($webhookConfiguration->endpoint, $data)
                ->throw();

            $successful = now();
        } catch (Exception $exception) {
            report($exception->getMessage());
            $successful = null;
        }

        $webhookConfiguration->webhooks()->create([
            'payload' => $data,
            'successful_at' => $successful,
            'event' => $this->eventName,
            'endpoint' => $webhookConfiguration->endpoint,
        ]);
    }
}
