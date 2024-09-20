<?php

namespace App\Listeners;

use App\Jobs\ProcessWebhook;
use App\Models\WebhookConfiguration;

class DispatchWebhooks
{
    public function handle(string $eventName, array $data): void
    {
        // todo: cache webhook configs

        foreach (WebhookConfiguration::all() as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                ProcessWebhook::dispatch($webhookConfig, $eventName, $data);
            }
        }
    }
}
