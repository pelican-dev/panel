<?php

namespace App\Services;

use App\Enums\WebhookScope;
use App\Jobs\ProcessWebhook;
use App\Models\Server;
use App\Models\WebhookConfiguration;

class WebhookService
{
    /**
     * @param  array<string, mixed>  $contextualData
     */
    public function dispatch(string $eventName, array $contextualData, ?Server $server = null): void
    {
        if ($server) {
            $webhooks = $server->webhookConfigurations()
                ->whereJsonContains('events', $eventName)
                ->get();

            foreach ($webhooks as $webhook) {
                ProcessWebhook::dispatch($webhook, $eventName, [$contextualData]);
            }
        }

        $globalWebhooks = WebhookConfiguration::query()
            ->where('scope', WebhookScope::Global)
            ->whereJsonContains('events', $eventName)
            ->get();

        foreach ($globalWebhooks as $webhook) {
            ProcessWebhook::dispatch($webhook, $eventName, [$contextualData]);
        }
    }

    /**
     * @return array<string, string>
     */
    public function getAllEvents(WebhookScope $scope = WebhookScope::Global): array
    {
        return WebhookConfiguration::filamentCheckboxList($scope);
    }
}
