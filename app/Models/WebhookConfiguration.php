<?php

namespace App\Models;

use App\Jobs\ProcessWebhook;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportEvents\HandlesEvents;
use App\Enums\WebhookType;

/**
 * @property string|null $type
 * @property string|null $payload
 * @property string $endpoint
 * @property string $description
 * @property string[] $events
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class WebhookConfiguration extends Model
{
    use HandlesEvents, HasFactory, SoftDeletes;

    /** @var string[] */
    protected static array $eventBlacklist = [
        'eloquent.created: App\Models\Webhook',
    ];

    protected $fillable = [
        'type',
        'payload',
        'endpoint',
        'description',
        'events',
    ];

    /**
     * Default values for specific fields in the database.
     */
    protected $attributes = [
        'type' => WebhookType::Standalone->value,
        'payload' => null,
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'payload' => 'array',
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

    /** @return string[] */
    public static function allPossibleEvents(): array
    {
        return collect(static::discoverCustomEvents())
            ->merge(static::allModelEvents())
            ->unique()
            ->filter(fn ($event) => !in_array($event, static::$eventBlacklist))
            ->all();
    }

    /** @return array<string, string> */
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

    /** @return string[] */
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

    /** @return string[] */
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

    /** @return string[] */
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

    /**
     * @param  array<mixed, mixed>  $replacement
     * @return array<mixed, mixed>|string|null
     * */
    public function replaceVars(array $replacement, string $subject): array|string|null
    {
        return preg_replace_callback(
            '/{{(.*?)}}/',
            function ($matches) use ($replacement) {
                $trimmed = trim($matches[1]);

                return Arr::get($replacement, $trimmed, $trimmed);
            },
            $subject
        );
    }

    public static function getTime(): ?string
    {
        return 'Today at ' . Carbon::now()->format('h:i A');
    }

    /** @return array<string, mixed> */
    public function run(?bool $dry = false): array
    {
        $eventName = collect($this->events ?: ['eloquent.created: App\\Models\\Server'])->random();
        $data = array_merge(Server::factory()->makeOne()->attributesToArray(), [
            'id' => random_int(1, 100),
            'event' => $this->transformClassName($eventName),
        ]);
        $eventData = [json_encode($data)];

        ProcessWebhook::dispatchIf(!$dry, $this, $eventName, $eventData);

        return $data;
    }
}
