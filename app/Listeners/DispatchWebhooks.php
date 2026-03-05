<?php

namespace App\Listeners;

use App\Enums\WebhookScope;
use App\Events\ActivityLogged;
use App\Models\Server;
use App\Models\WebhookConfiguration;
use App\Services\WebhookService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DispatchWebhooks
{

    public function handle(mixed $event, ?string $action = null): void
    {
        if ($event instanceof ActivityLogged) {
            $this->handleActivityLogged($event);
            $this->handleGlobalWebhooks($event);
        } elseif ($event instanceof Model) {
            $detectedAction = $action ?? $this->determineEloquentAction($event);
            if ($detectedAction) {
                $this->handleEloquentEvent($event, $detectedAction);
            }
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

    protected function determineEloquentAction(Model $model): ?string
    {
        if ($model->wasRecentlyCreated) {
            return 'created';
        }

        if ($model->usesTimestamps() && $model->hasAttribute($model->getDeletedAtColumn())) {
            $deletedAt = $model->{$model->getDeletedAtColumn()};
            if ($deletedAt && now()->diffInSeconds($deletedAt) < 5) {
                return 'deleted';
            }
        }

        return 'updated';
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
            $server = $firstSubject->subject;
        } elseif (isset($activityLogged->model->properties['server'])) {
            $server = Server::find($activityLogged->model->properties['server']['id'] ?? null);
        }


        $webhooks = $server->webhooks()
            ->whereJsonContains('events', $eventName)
            ->get();

        foreach ($webhooks as $_) {
            WebhookService::dispatch($eventName, $activityLogged->model->properties?->toArray() ?? [], $server);
        }
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

        $activityLogData = $activityLogged->model->toArray();
        $webhookData = [
            'event' => $eventName,
            'description' => $activityLogged->model->description,
            'ip' => $activityLogged->model->ip,
            'timestamp' => $activityLogged->model->timestamp?->toIso8601String(),
        ];

        if ($activityLogged->model->actor_id) {
            $actor = $activityLogged->model->actor;
            $webhookData['actor'] = [
                'id' => $activityLogged->model->actor_id,
                'type' => $activityLogged->model->actor_type,
                'name' => $actor?->name ?? null,
                'email' => $actor?->email ?? null,
            ];
        }

        if ($activityLogged->model->properties?->isNotEmpty()) {
            $webhookData['properties'] = $activityLogged->model->properties->toArray();
        }

        if ($activityLogged->model->subjects->isNotEmpty()) {
            $webhookData['subjects'] = $activityLogged->model->subjects->map(function ($subject) {
                return [
                    'id' => $subject->subject_id,
                    'type' => $subject->subject_type,
                ];
            })->toArray();
        }

        if (!$this->hasPayloadContent($webhookData)) {
            return;
        }

        /** @var WebhookConfiguration $webhookConfig */
        foreach ($matchingHooks as $webhookConfig) {
            if (in_array($eventName, $webhookConfig->events)) {
                $webhookConfig->run($eventName, [$webhookData]);
            }
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


    protected function hasPayloadContent(array $webhookData): bool
    {
        $contentData = array_filter($webhookData, function ($value, $key) {
            return $key !== 'event';
        }, ARRAY_FILTER_USE_BOTH);

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