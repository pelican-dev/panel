<?php

namespace App\Models;

use App\Jobs\ProcessWebhook;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Livewire\Features\SupportEvents\HandlesEvents;
use App\Enums\WebhookType;

/**
 * @property string|array<string, mixed>|null $payload
 * @property string $endpoint
 * @property string $description
 * @property string[] $events
 * @property WebhookType|string|null $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property array<string, string>|null $headers
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
        'headers',
    ];

    /**
     * Default values for specific fields in the database.
     */
    protected $attributes = [
        'type' => WebhookType::Regular,
        'payload' => null,
    ];

    protected function casts(): array
    {
        return [
            'events' => 'array',
            'payload' => 'array',
            'type' => WebhookType::class,
            'headers' => 'array',
        ];
    }

    protected static function booted(): void
    {
        self::saved(static function (self $webhookConfiguration): void {
            $changedEvents = collect([
                ...($webhookConfiguration->events),
                ...$webhookConfiguration->getOriginal('events', '[]'),
            ])->unique();

            self::updateCache($changedEvents);
        });

        self::deleted(static function (self $webhookConfiguration): void {
            self::updateCache(collect($webhookConfiguration->events));
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
                ->replace([DIRECTORY_SEPARATOR, '\\app\\'], ['\\', 'App\\']);

            $events[] = $namespace . '\\' . str($file->getFilename())
                ->replace([DIRECTORY_SEPARATOR, '.php'], ['\\', '']);
        }

        return $events;
    }

    /**
     * @param  array<mixed, mixed>  $replacement
     * */
    public function replaceVars(array $replacement, string $subject): string
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

    /** @param array<mixed, mixed> $eventData */
    public function run(?string $eventName = null, ?array $eventData = null): void
    {
        $eventName ??= 'eloquent.created: '.Server::class;
        $eventData ??= $this->getWebhookSampleData();

        ProcessWebhook::dispatch($this, $eventName, [$eventData]);
    }

    /**
     * @return array<string, mixed>
     */
    public function getWebhookSampleData(): array
    {
        return [
            'status' => 'installing',
            'oom_killer' => false,
            'installed_at' => null,
            'external_id' => 10,
            'uuid' => '651fgbc1-dee6-4250-814e-10slda13f1e',
            'uuid_short' => '651fgbc1',
            'node_id' => 1,
            'name' => 'Eagle',
            'description' => 'This is an example server description.',
            'skip_scripts' => false,
            'owner_id' => 1,
            'memory' => 2048,
            'swap' => 128,
            'disk' => 10240,
            'io' => 500,
            'cpu' => 100,
            'threads' => '1,3,5',
            'allocation_id' => 4,
            'egg_id' => 2,
            'startup' => 'java -Xms128M -XX:MaxRAMPercentage=95.0 -jar {{SERVER_JARFILE}}',
            'image' => 'ghcr.io/parkervcp/yolks:java_21',
            'database_limit' => 1,
            'allocation_limit' => 5,
            'backup_limit' => 3,
            'docker_labels' => [],
            'created_at' => '2025-03-17T15:20:32.000000Z',
            'updated_at' => '2025-05-12T17:53:12.000000Z',
            'id' => 2,
        ];
    }
}
