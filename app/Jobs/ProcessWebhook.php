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
        $data = $this->data[0];

        if ($this->webhookConfiguration->type === 'discord') {
            $data = array_merge(
                json_decode($data, true),
                ['event' => $this->webhookConfiguration->transformClassName($this->eventName)]
            );

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
            Http::withHeader('X-Webhook-Event', $this->eventName)
                ->post($this->webhookConfiguration->endpoint, $data)
                ->throw();
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