<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * @property string $endpoint
 * @property string $description
 * @property array $events
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class WebhookConfiguration extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Blacklisted events.
     */
    protected static array $eventBlacklist = [
        'eloquent.created: App\Models\Webhook',
    ];

    protected $fillable = [
        'endpoint',
        'description',
        'events',
    ];

    protected function casts(): array
    {
        return [
            'events' => 'json',
        ];
    }

    protected static function booted(): void
    {
        self::saved(static function (self $webhookConfiguration): void {
            $changedEvents = collect([
                ...((array) $webhookConfiguration->events),
                ...$webhookConfiguration->getOriginal('events', '[]'),
            ])->unique();

            self::updateCache($changedEvents);
        });

        self::deleted(static function (self $webhookConfiguration): void {
            self::updateCache(collect((array) $webhookConfiguration->events));
        });
    }

    private static function updateCache(Collection $eventList): void
    {
        $eventList->each(function (string $event) {
            cache()->forever("webhooks.$event", WebhookConfiguration::query()->whereJsonContains('events', $event)->get());
        });

        cache()->forever('watchedWebhooks', WebhookConfiguration::pluck('events')->flatten()->unique()->values()->all());
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public static function allPossibleEvents(): array
    {
        return collect(static::discoverCustomEvents())
            ->merge(static::allModelEvents())
            ->unique()
            ->filter(fn ($event) => !in_array($event, static::$eventBlacklist))
            ->all();
    }

    public static function filamentCheckboxList(): array
    {
        $list = [];
        $events = static::allPossibleEvents();
        foreach ($events as $event) {
            $list[$event] = static::transformClassName($event);
        }

        return $list;
    }

    public static function transformClassName(string $event): string
    {
        return str($event)
            ->after('eloquent.')
            ->replace('App\\Models\\', '')
            ->replace('App\\Events\\', 'event: ')
            ->toString();
    }

    public static function allModelEvents(): array
    {
        $eventTypes = ['created', 'updated', 'deleted'];
        $models = static::discoverModels();

        $events = [];
        foreach ($models as $model) {
            foreach ($eventTypes as $eventType) {
                $events[] = "eloquent.$eventType: $model";
            }
        }

        return $events;
    }

    public static function discoverModels(): array
    {
        $namespace = 'App\\Models\\';
        $directory = app_path('Models');

        $models = [];
        foreach (File::allFiles($directory) as $file) {
            $models[] = $namespace . str($file->getFilename())
                ->replace([DIRECTORY_SEPARATOR, '.php'], ['\\', '']);
        }

        return $models;
    }

    public static function discoverCustomEvents(): array
    {
        $directory = app_path('Events');

        $events = [];
        foreach (File::allFiles($directory) as $file) {
            $namespace = str($file->getPath())
                ->after(base_path())
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->replace('\\app\\', 'App\\')
                ->toString();

            $events[] = $namespace . '\\' . str($file->getFilename())
                ->replace([DIRECTORY_SEPARATOR, '.php'], ['\\', '']);
        }

        return $events;
    }
}
