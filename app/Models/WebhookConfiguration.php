<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Jobs\ProcessWebhook;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
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

                return data_get($replacement, $trimmed, $trimmed);
            },
            $subject
        );
    }

    /** @param array<mixed, mixed> $eventData */
    public function run(?string $eventName = null, ?array $eventData = null): void
    {
        $eventName ??= 'eloquent.created: '.Server::class;
        $eventData ??= static::getWebhookSampleData();

        ProcessWebhook::dispatch($this, $eventName, [$eventData]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function getWebhookSampleData(): array
    {
        return [
            'id' => 4,
            'uuid' => '4864a058-9a3b-44a9-a6cf-c1355e89406e',
            'uuid_short' => '4864a058',
            'node_id' => 1,
            'name' => 'Example Server',
            'owner_id' => 1,
            'memory' => 6144,
            'swap' => 0,
            'disk' => 20480,
            'io' => 500,
            'cpu' => 300,
            'egg_id' => 1,
            'startup' => 'java -Xms128M -XX:MaxRAMPercentage=95.0 -Dterminal.jline=false -Dterminal.ansi=true -jar {{SERVER_JARFILE}}',
            'created_at' => '2025-09-05T01:15:43.000000Z',
            'updated_at' => '2025-09-11T22:45:14.000000Z',
            'allocation_id' => 4,
            'image' => 'ghcr.io/parkervcp/yolks:java_21',
            'description' => 'This is an example server description.',
            'skip_scripts' => false,
            'external_id' => null,
            'database_limit' => 5,
            'allocation_limit' => 5,
            'threads' => null,
            'backup_limit' => 5,
            'status' => null,
            'installed_at' => '2025-09-06T03:02:31.000000Z',
            'oom_killer' => false,
            'docker_labels' => [],
            'allocation' => [
                'id' => 4,
                'node_id' => 1,
                'ip' => '0.0.0.0',
                'port' => 25565,
                'server_id' => 4,
                'created_at' => '2025-07-01T20:12:41.000000Z',
                'updated_at' => '2025-09-09T17:47:22.000000Z',
                'ip_alias' => null,
                'notes' => null,
            ],
            'variables' => [
                [
                    'id' => 1,
                    'egg_id' => 1,
                    'name' => 'Build Number',
                    'description' => 'The build number for the paper release.\r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.',
                    'env_variable' => 'BUILD_NUMBER',
                    'default_value' => 'latest',
                    'user_viewable' => true,
                    'user_editable' => true,
                    'rules' => ['required', 'string', 'max:20'],
                    'created_at' => '2025-09-05T01:15:43.000000Z',
                    'updated_at' => '2025-09-05T01:15:43.000000Z',
                    'sort' => 4,
                    'server_value' => 'latest',
                ],
                [
                    'id' => 2,
                    'egg_id' => 1,
                    'name' => 'Download Path',
                    'description' => 'A URL to use to download a server.jar rather than the ones in the install script. This is not user\nviewable.',
                    'env_variable' => 'DL_PATH',
                    'default_value' => '',
                    'user_viewable' => false,
                    'user_editable' => false,
                    'rules' => ['nullable', 'string'],
                    'created_at' => '2025-09-05T01:15:43.000000Z',
                    'updated_at' => '2025-09-05T01:15:43.000000Z',
                    'sort' => 3,
                    'server_value' => '',
                ],
                [
                    'id' => 3,
                    'egg_id' => 1,
                    'name' => 'Minecraft Version',
                    'description' => 'The version of minecraft to download. \r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.',
                    'env_variable' => 'MINECRAFT_VERSION',
                    'default_value' => 'latest',
                    'user_viewable' => true,
                    'user_editable' => true,
                    'rules' => ['nullable', 'string', 'max:20'],
                    'created_at' => '2025-09-05T01:15:43.000000Z',
                    'updated_at' => '2025-09-05T01:15:43.000000Z',
                    'sort' => 1,
                    'server_value' => '1.21.8',
                ],
                [
                    'id' => 4,
                    'egg_id' => 1,
                    'name' => 'Server Jar File',
                    'description' => 'The name of the server jarfile to run the server with.',
                    'env_variable' => 'SERVER_JARFILE',
                    'default_value' => 'server.jar',
                    'user_viewable' => true,
                    'user_editable' => true,
                    'rules' => ['required', 'regex:/^([\w\d._-]+)(\.jar)$/'],
                    'created_at' => '2025-09-05T01:15:43.000000Z',
                    'updated_at' => '2025-09-05T01:15:43.000000Z',
                    'sort' => 2,
                    'server_value' => 'server.jar',
                ],
            ],
            'server_variables' => [
                'record-21' => [
                    'id' => 21,
                    'server_id' => 4,
                    'variable_id' => 3,
                    'variable_value' => '1.21.8',
                    'created_at' => '2025-09-06T06:00:58.000000Z',
                    'updated_at' => '2025-09-09T17:59:40.000000Z',
                    'variable' => [
                        'id' => 3,
                        'egg_id' => 1,
                        'name' => 'Minecraft Version',
                        'description' => 'The version of minecraft to download. \r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.',
                        'env_variable' => 'MINECRAFT_VERSION',
                        'default_value' => 'latest',
                        'user_viewable' => true,
                        'user_editable' => true,
                        'rules' => ['nullable', 'string', 'max:20'],
                        'created_at' => '2025-09-05T01:15:43.000000Z',
                        'updated_at' => '2025-09-05T01:15:43.000000Z',
                        'sort' => 1,
                    ],
                ],
                'record-22' => [
                    'id' => 22,
                    'server_id' => 4,
                    'variable_id' => 4,
                    'variable_value' => 'server.jar',
                    'created_at' => '2025-09-06T06:00:58.000000Z',
                    'updated_at' => '2025-09-06T06:01:05.000000Z',
                    'variable' => [
                        'id' => 4,
                        'egg_id' => 1,
                        'name' => 'Server Jar File',
                        'description' => 'The name of the server jarfile to run the server with.',
                        'env_variable' => 'SERVER_JARFILE',
                        'default_value' => 'server.jar',
                        'user_viewable' => true,
                        'user_editable' => true,
                        'rules' => ['required', 'regex:/^([\w\d._-]+)(\.jar)$/'],
                        'created_at' => '2025-09-05T01:15:43.000000Z',
                        'updated_at' => '2025-09-05T01:15:43.000000Z',
                        'sort' => 2,
                    ],
                ],
                'record-20' => [
                    'id' => 20,
                    'server_id' => 4,
                    'variable_id' => 2,
                    'variable_value' => '',
                    'created_at' => '2025-09-06T06:00:58.000000Z',
                    'updated_at' => '2025-09-06T06:00:58.000000Z',
                    'variable' => [
                        'id' => 2,
                        'egg_id' => 1,
                        'name' => 'Download Path',
                        'description' => 'A URL to use to download a server.jar rather than the ones in the install script. This is not user\nviewable.',
                        'env_variable' => 'DL_PATH',
                        'default_value' => '',
                        'user_viewable' => false,
                        'user_editable' => false,
                        'rules' => ['nullable', 'string'],
                        'created_at' => '2025-09-05T01:15:43.000000Z',
                        'updated_at' => '2025-09-05T01:15:43.000000Z',
                        'sort' => 3,
                    ],
                ],
                'record-19' => [
                    'id' => 19,
                    'server_id' => 4,
                    'variable_id' => 1,
                    'variable_value' => 'latest',
                    'created_at' => '2025-09-06T06:00:58.000000Z',
                    'updated_at' => '2025-09-06T06:00:58.000000Z',
                    'variable' => [
                        'id' => 1,
                        'egg_id' => 1,
                        'name' => 'Build Number',
                        'description' => 'The build number for the paper release.\r\n\r\nLeave at latest to always get the latest version. Invalid versions will default to latest.',
                        'env_variable' => 'BUILD_NUMBER',
                        'default_value' => 'latest',
                        'user_viewable' => true,
                        'user_editable' => true,
                        'rules' => ['required', 'string', 'max:20'],
                        'created_at' => '2025-09-05T01:15:43.000000Z',
                        'updated_at' => '2025-09-05T01:15:43.000000Z',
                        'sort' => 4,
                    ],
                ],
            ],
            'event' => 'updated: Server',
        ];
    }
}
