<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\WebhookConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessWebhook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private WebhookConfiguration $webhookConfiguration,
        private string $eventName,
        private array $data
    ) {
    }

    public function handle(): void
    {
        try {
            Http::withHeader('X-Webhook-Event', $this->eventName)
                ->post($this->webhookConfiguration->endpoint, $this->data)
                ->throw();
            $successful = now();
        } catch (\Exception) {
            $successful = null;
        }

        $this->webhookConfiguration->webhooks()->create([
            'payload' => $this->data,
            'successful_at' => $successful,
            'event' => $this->eventName,
            'endpoint' => $this->webhookConfiguration->endpoint,
        ]);
    }
}
