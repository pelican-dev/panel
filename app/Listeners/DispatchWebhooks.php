<?php

namespace App\Listeners;

use App\Events\Event;
use App\Jobs\DispatchWebhooksJob;
use App\Models\WebhookConfiguration;
use Illuminate\Support\Facades\Http;

class DispatchWebhooks
{
    public function handle(string $eventName, array $data): void
    {
        foreach (WebhookConfiguration::all() as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                $this->callWehbook($webhookConfig, $data);
            }
        }
    }

    private function callWehbook(WebhookConfiguration $wh, $data) {
        Http::post($wh->endpoint, $data);
    }
}
