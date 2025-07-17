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
        private WebhookConfiguration $webhookConfiguration,
        private string $eventName,
        private array $data
    ) {}

    public function handle(): void
    {
        $data = $this->data[0] ?? [];
        if (count($data) === 1) {
            $data = reset($data);
        }
        $data = is_array($data) ? $data : (json_decode($data, true) ?? []);
        $data['event'] = $this->webhookConfiguration->transformClassName($this->eventName);

        if ($this->webhookConfiguration->type === WebhookType::Discord) {
            $payload = json_encode($this->webhookConfiguration->payload);
            $tmp = $this->webhookConfiguration->replaceVars($data, $payload);
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

        try {
            $customHeaders = $this->webhookConfiguration->headers;
            $headers = [];
            foreach ($customHeaders as $key => $value) {
                $headers[$key] = $this->webhookConfiguration->replaceVars($data, $value);
            }

            Http::withHeaders($headers)->post($this->webhookConfiguration->endpoint, $data)->throw();
            $successful = now();
        } catch (Exception $exception) {
            report($exception->getMessage());
            $successful = null;
        }

        $this->webhookConfiguration->webhooks()->create([
            'payload' => $data,
            'successful_at' => $successful,
            'event' => $this->eventName,
            'endpoint' => $this->webhookConfiguration->endpoint,
        ]);
    }
}
