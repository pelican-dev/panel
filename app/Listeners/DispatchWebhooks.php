<?php

namespace App\Listeners;

use App\Jobs\ProcessWebhook;
use App\Models\WebhookConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DispatchWebhooks
{
    public function handle(string $eventName, array $data): void
    {
        foreach (WebhookConfiguration::all() as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                ProcessWebhook::dispatchSync($webhookConfig, $eventName, $data);
            }
        }
    }
}
