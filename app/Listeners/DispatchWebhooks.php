<?php

namespace App\Listeners;

use App\Events\Event;
use App\Jobs\DispatchWebhooksJob;
use App\Models\Webhook;
use App\Models\WebhookConfiguration;
use Illuminate\Support\Facades\Http;

class DispatchWebhooks
{
    public function handle(string $eventName, array $data): void
    {
        foreach (WebhookConfiguration::all() as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                $this->callWebhook($webhookConfig, $eventName, $data);
            }
        }
    }

    private function callWebhook(WebhookConfiguration $wh, $eventName, $data) {
        try {
            Http::post($wh->endpoint, $data)->throw();
            $successful = now();
        } catch (\Exception) {
            $successful = null;
        }

        $wh->webhooks()->create([
            'payload' => $data,
            'successful_at' => $successful,
            'event' => $eventName,
            'endpoint' => $wh->endpoint,
        ]);
    }
}
