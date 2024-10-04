<?php

namespace App\Listeners;

use App\Jobs\ProcessWebhook;
use App\Models\WebhookConfiguration;

class DispatchWebhooks
{
    public function handle(string $eventName, array $data): void
    {
        $matchingHooks = cache()->rememberForever("webhooks.$eventName", function () use ($eventName) {
            return WebhookConfiguration::query()->whereJsonContains('events', $eventName)->get();
        });

        foreach ($matchingHooks ?? [] as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                ProcessWebhook::dispatch($webhookConfig, $eventName, $data);
            }
        }
    }
}
