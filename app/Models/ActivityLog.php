<?php

namespace App\Models;

use App\Traits\HasValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\ActivityLogged;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * \App\Models\ActivityLog.
 *
 * @property int $id
 * @property string|null $batch
 * @property string $event
 * @property string $ip
 * @property string|null $description
 * @property string|null $actor_type
 * @property int|null $actor_id
 * @property int|null $api_key_id
 * @property \Illuminate\Support\Collection|null $properties
 * @property \Carbon\Carbon $timestamp
 * @property Model|\Eloquent $actor
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ActivityLogSubject[] $subjects
 * @property int|null $subjects_count
 * @property \App\Models\ApiKey|null $apiKey
 *
 * @method static Builder|ActivityLog forActor(Model $actor)
 * @method static Builder|ActivityLog forEvent(string $action)
 * @method static Builder|ActivityLog newModelQuery()
 * @method static Builder|ActivityLog newQuery()
 * @method static Builder|ActivityLog query()
 * @method static Builder|ActivityLog whereActorId($value)
 * @method static Builder|ActivityLog whereActorType($value)
 * @method static Builder|ActivityLog whereApiKeyId($value)
 * @method static Builder|ActivityLog whereBatch($value)
 * @method static Builder|ActivityLog whereDescription($value)
 * @method static Builder|ActivityLog whereEvent($value)
 * @method static Builder|ActivityLog whereId($value)
 * @method static Builder|ActivityLog whereIp($value)
 * @method static Builder|ActivityLog whereProperties($value)
 * @method static Builder|ActivityLog whereTimestamp($value)
 */
class ActivityLog extends Model implements HasIcon, HasLabel
{
    use HasValidation;
    use MassPrunable;

    public const RESOURCE_NAME = 'activity_log';

    /**
     * Tracks all the events we no longer wish to display to users. These are either legacy
     * events or just events where we never ended up using the associated data.
     */
    public const DISABLED_EVENTS = ['server:file.upload'];

    public $timestamps = false;

    protected $guarded = [
        'id',
        'timestamp',
    ];

    protected $with = ['subjects'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'event' => ['required', 'string'],
        'batch' => ['nullable', 'uuid'],
        'ip' => ['required', 'string'],
        'description' => ['nullable', 'string'],
        'properties' => ['array'],
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'collection',
            'timestamp' => 'datetime',
        ];
    }

    public function actor(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    /**
     * @return HasMany<ActivityLogSubject, $this>
     */
    public function subjects(): HasMany
    {
        return $this->hasMany(ActivityLogSubject::class);
    }

    public function apiKey(): HasOne
    {
        return $this->hasOne(ApiKey::class, 'id', 'api_key_id');
    }

    public function scopeForEvent(Builder $builder, string $action): Builder
    {
        return $builder->where('event', $action);
    }

    /**
     * Scopes a query to only return results where the actor is a given model.
     */
    public function scopeForActor(Builder $builder, Model $actor): Builder
    {
        return $builder->whereMorphedTo('actor', $actor);
    }

    /**
     * Returns models to be pruned.
     *
     * @see https://laravel.com/docs/9.x/eloquent#pruning-models
     */
    public function prunable(): Builder
    {
        if (is_null(config('activity.prune_days'))) {
            throw new \LogicException('Cannot prune activity logs: no "prune_days" configuration value is set.');
        }

        return static::where('timestamp', '<=', Carbon::now()->subDays(config('activity.prune_days')));
    }

    /**
     * Boots the model event listeners. This will trigger an activity log event every
     * time a new model is inserted which can then be captured and worked with as needed.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->timestamp = Carbon::now();
        });

        static::created(function (self $model) {
            Event::dispatch(new ActivityLogged($model));
        });
    }

    public function getIcon(): string
    {
        if ($this->apiKey) {
            return 'tabler-api';
        }

        if ($this->actor instanceof User) {
            return 'tabler-user';
        }

        return $this->actor_id === null ? 'tabler-device-desktop' : 'tabler-user-off';
    }

    public function getLabel(): string
    {
        $properties = $this->wrapProperties();

        return trans_choice('activity.'.str($this->event)->replace(':', '.'), array_key_exists('count', $properties) ? $properties['count'] : 1, $properties);
    }

    public function htmlable(): string
    {
        $user = $this->actor;
        if (!$user instanceof User) {
            $user = new User([
                'email' => 'system@pelican.dev',
                'username' => 'system',
            ]);
        }

        return "
            <div style='display: flex; align-items: center;'>
                <img width='50px' height='50px' src='{$user->getFilamentAvatarUrl()}' style='margin-right: 15px' />

                <div>
                    <p>$user->username — $this->event</p>
                    <p>{$this->getLabel()}</p>
                    <p>$this->ip — <span title='{$this->timestamp->format('M j, Y g:ia')}'>{$this->timestamp->diffForHumans()}</span></p>
                </div>
            </div>
        ";
    }

    /**
     * @return array<string, string>
     */
    public function wrapProperties(): array
    {
        if (!$this->properties || $this->properties->isEmpty()) {
            return [];
        }

        $properties = $this->properties->mapWithKeys(function ($value, $key) {
            if (!is_array($value)) {
                // Perform some directory normalization at this point.
                if ($key === 'directory') {
                    $value = str_replace('//', '/', '/' . trim($value, '/') . '/');
                }

                return [$key => $value];
            }

            $first = array_first($value);

            // Backwards compatibility for old logs
            if (is_array($first)) {
                return ["{$key}_count" => count($value)];
            }

            return [$key => $first, "{$key}_count" => count($value)];
        });

        $keys = $properties->keys()->filter(fn ($key) => Str::endsWith($key, '_count'))->values();
        if ($keys->containsOneItem()) {
            $properties = $properties->merge(['count' => $properties->get($keys[0])])->except([$keys[0]]);
        }

        return $properties->toArray();
    }

    /**
     * Determines if there are any log properties that we've not already exposed
     * in the response language string and that are not just the IP address or
     * the browser useragent.
     *
     * This is used by the front-end to selectively display an "additional metadata"
     * button that is pointless if there is nothing the user can't already see from
     * the event description.
     */
    public function hasAdditionalMetadata(): bool
    {
        if (!$this->properties || $this->properties->isEmpty()) {
            return false;
        }

        $properties = $this->wrapProperties();
        $event = trans_choice('activity.'.str($this->event)->replace(':', '.'), array_key_exists('count', $properties) ? $properties['count'] : 1);

        preg_match_all('/:(?<key>[\w.-]+\w)(?:[^\w:]?|$)/', $event, $matches);

        $exclude = array_merge($matches['key'], ['ip', 'useragent', 'using_sftp']);
        foreach ($this->properties->keys() as $key) {
            if (!in_array($key, $exclude, true)) {
                return true;
            }
        }

        return false;
    }
}
