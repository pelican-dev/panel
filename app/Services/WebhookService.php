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
    public static function dispatch(string $eventName, array $contextualData, ?Server $server = null): void
    {
        if ($server) {
            $webhooks = $server->webhooks()
                ->whereJsonContains('events', $eventName)
                ->get();

            foreach ($webhooks as $webhook) {
                ProcessWebhook::dispatch($webhook, $eventName, [$contextualData]);
            }
        }

        $globalWebhooks = WebhookConfiguration::query()
            ->where('scope', WebhookScope::GLOBAL)
            ->whereJsonContains('events', $eventName)
            ->get();

        foreach ($globalWebhooks as $webhook) {
            ProcessWebhook::dispatch($webhook, $eventName, [$contextualData]);
        }
    }

    /**
     * @return array<string, string>
     */
    public static function getAllEvents(WebhookScope $scope = WebhookScope::GLOBAL): array
    {
        return WebhookConfiguration::filamentCheckboxList($scope);
    }
}
