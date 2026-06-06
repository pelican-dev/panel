<?php

namespace App\Listeners;

use App\Enums\WebhookScope;
use App\Events\ActivityLogged;
use App\Models\Server;
use App\Models\User;
use App\Models\WebhookConfiguration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DispatchWebhooks
{
    /** @param array<mixed>|string|null $action */
    public function handle(mixed $event, array|string|null $action = null): void
    {
        if (is_string($event) && is_array($action)) {
            if (str_starts_with($event, 'eloquent.')) {
                $this->handleEloquentEvent($action[0], str($event)->between('eloquent.', ':'));
            } elseif ($event !== ActivityLogged::class) {
                $this->handleGenericClassEvent($event, $action);
            }

            return;
        }

        if ($event instanceof ActivityLogged) {
            $this->handleActivityLogged($event);
            $this->handleGlobalWebhooks($event);
        }
    }

    protected function handleEloquentEvent(Model $model, string $action): void
    {
        $modelClass = $model::class;
        $eventName = "eloquent.$action: $modelClass";

        $webhooks = WebhookConfiguration::query()
            ->where('scope', WebhookScope::GLOBAL)
            ->whereJsonContains('events', $eventName)
            ->get();

        if ($webhooks->isEmpty()) {
            return;
        }

        $webhookData = [
            'event' => $eventName,
            'data' => $model->toArray(),
            'timestamp' => now()->toIso8601String(),
        ];

        if (!$this->hasPayloadContent($webhookData)) {
            return;
        }

        /** @var WebhookConfiguration $webhookConfig */
        foreach ($webhooks as $webhookConfig) {
            $webhookConfig->run($eventName, [$webhookData]);
        }
    }

    /** @param array<mixed> $payload */
    protected function handleGenericClassEvent(string $eventName, array $payload): void
    {
        if (!$this->eventIsWatched($eventName)) {
            return;
        }

        $matchingHooks = cache()->rememberForever("webhooks.$eventName", function () use ($eventName) {
            return WebhookConfiguration::query()
                ->where('scope', WebhookScope::GLOBAL)
                ->whereJsonContains('events', $eventName)
                ->get();
        });

        if ($matchingHooks->isEmpty()) {
            return;
        }

        $obj = $payload[0] ?? null;
        $webhookData = ['event' => $eventName, 'timestamp' => now()->toIso8601String()];
        if (is_object($obj) && method_exists($obj, 'toArray')) {
            $webhookData['data'] = $obj->toArray();
        } elseif (is_array($obj)) {
            $webhookData['data'] = $obj;
        }

        foreach ($matchingHooks as $webhookConfig) {
            $webhookConfig->run($eventName, [$webhookData]);
        }
    }

    protected function handleActivityLogged(ActivityLogged $activityLogged): void
    {
        $eventName = $activityLogged->model->event;

        if (!$activityLogged->isServerEvent()) {
            return;
        }

        $server = null;
        $firstSubject = $activityLogged->model->subjects->first();
        if ($firstSubject && $firstSubject->subject_type === (new Server())->getMorphClass()) {
            $subject = $firstSubject->subject;
            $server = $subject instanceof Server ? $subject : null;
        } elseif (isset($activityLogged->model->properties['server'])) {
            $server = Server::find($activityLogged->model->properties['server']['id'] ?? null);
        }

        if (!$server) {
            return;
        }

        $webhooks = $server->webhooks()
            ->whereJsonContains('events', $eventName)
            ->get();

        if ($webhooks->isEmpty()) {
            return;
        }

        $webhookData = $this->buildActivityPayload($activityLogged);

        if (!$this->hasPayloadContent($webhookData)) {
            return;
        }

        foreach ($webhooks as $webhookConfig) {
            $webhookConfig->run($eventName, [$webhookData]);
        }
    }

    /** @return array<string, mixed> */
    protected function buildActivityPayload(ActivityLogged $activityLogged): array
    {
        $webhookData = [
            'event' => $activityLogged->model->event,
            'description' => $activityLogged->model->description,
            'ip' => $activityLogged->model->ip,
            'timestamp' => $activityLogged->model->timestamp->toIso8601String(),
        ];

        if ($activityLogged->model->actor_id) {
            $actor = $activityLogged->model->actor;
            $webhookData['actor'] = [
                'id' => $activityLogged->model->actor_id,
                'type' => $activityLogged->model->actor_type,
                'username' => $actor instanceof User ? $actor->username : null,
                'email' => $actor instanceof User ? $actor->email : null,
            ];
        }

        if ($activityLogged->model->properties->isNotEmpty()) {
            $webhookData['properties'] = $activityLogged->model->properties->toArray();
        }

        if ($activityLogged->model->subjects->isNotEmpty()) {
            $webhookData['subjects'] = $activityLogged->model->subjects->map(fn ($subject) => [
                'id' => $subject->subject_id,
                'type' => $subject->subject_type,
            ])->toArray();
        }

        return $webhookData;
    }

    protected function handleGlobalWebhooks(ActivityLogged $activityLogged): void
    {
        $eventName = $activityLogged->model->event;

        if (!$this->eventIsWatched($eventName)) {
            return;
        }

        $matchingHooks = cache()->rememberForever("webhooks.$eventName", function () use ($eventName) {
            return WebhookConfiguration::query()
                ->where('scope', WebhookScope::GLOBAL)
                ->whereJsonContains('events', $eventName)
                ->get();
        });

        $webhookData = $this->buildActivityPayload($activityLogged);

        if (!$this->hasPayloadContent($webhookData)) {
            return;
        }

        foreach ($matchingHooks as $webhookConfig) {
            $webhookConfig->run($eventName, [$webhookData]);
        }
    }

    protected function eventIsWatched(string $eventName): bool
    {
        $watchedEvents = cache()->rememberForever('watchedWebhooks', function () {
            return WebhookConfiguration::where('scope', WebhookScope::GLOBAL)
                ->pluck('events')
                ->flatten()
                ->unique()
                ->values()
                ->all();
        });

        return in_array($eventName, $watchedEvents);
    }

    /** @param array<mixed> $webhookData */
    protected function hasPayloadContent(array $webhookData): bool
    {
        $contentData = array_filter($webhookData, fn (mixed $_, string $key) => $key !== 'event', ARRAY_FILTER_USE_BOTH);

        $contentData = array_filter($contentData, function ($value) {
            if (is_array($value)) {
                return !empty($value);
            }
            if ($value instanceof Collection) {
                return $value->isNotEmpty();
            }

            return $value !== null && $value !== '';
        });

        return !empty($contentData);
    }
}
