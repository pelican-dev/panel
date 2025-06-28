<?php

namespace App\Listeners;

use App\Models\WebhookConfiguration;

class DispatchWebhooks
{
    /**
     * @param  array<mixed>  $data
     */
    public function handle(string $eventName, array $data): void
    {
        if (!$this->eventIsWatched($eventName)) {
            return;
        }

        $matchingHooks = cache()->rememberForever("webhooks.$eventName", function () use ($eventName) {
            return WebhookConfiguration::query()->whereJsonContains('events', $eventName)->get();
        });

        /** @var WebhookConfiguration $webhookConfig */
        foreach ($matchingHooks as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                $webhookConfig->run($eventName, $data);
            }
        }
    }

    protected function eventIsWatched(string $eventName): bool
    {
        $watchedEvents = cache()->rememberForever('watchedWebhooks', function () {
            return WebhookConfiguration::pluck('events')
                ->flatten()
                ->unique()
                ->values()
                ->all();
        });

        return in_array($eventName, $watchedEvents);
    }
}
