<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Enums\ContainerStatus;
use App\Enums\ServerResourceType;
use App\Enums\ServerState;
use App\Repositories\Daemon\DaemonServerRepository;
use App\Traits\HasValidation;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Number;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Exceptions\Http\Server\ServerStateConflictException;
use App\Services\Subusers\SubuserDeletionService;

/**
 * \App\Models\Server.
 *
 * @property int $id
 * @property string|null $external_id
 * @property string $uuid
 * @property string $uuid_short
 * @property int $node_id
 * @property string $name
 * @property string $description
 * @property ServerState|null $status
 * @property bool $skip_scripts
 * @property int $owner_id
 * @property int $memory
 * @property int $swap
 * @property int $disk
 * @property int $io
 * @property int $cpu
 * @property string|null $threads
 * @property bool $oom_killer
 * @property int $allocation_id
 * @property int $egg_id
 * @property string $startup
 * @property string $image
 * @property int|null $allocation_limit
 * @property int|null $database_limit
 * @property int|null $backup_limit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $installed_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\ActivityLog[] $activity
 * @property int|null $activity_count
 * @property \App\Models\Allocation|null $allocation
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Allocation[] $allocations
 * @property int|null $allocations_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Backup[] $backups
 * @property int|null $backups_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Database[] $databases
 * @property int|null $databases_count
 * @property \App\Models\Egg|null $egg
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Mount[] $mounts
 * @property int|null $mounts_count
 * @property \App\Models\Node $node
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property int|null $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Schedule[] $schedules
 * @property int|null $schedules_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Subuser[] $subusers
 * @property int|null $subusers_count
 * @property \App\Models\ServerTransfer|null $transfer
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\EggVariable[] $variables
 * @property int|null $variables_count
 *
 * @method static \Database\Factories\ServerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Server newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Server query()
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereAllocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereAllocationLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereBackupLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCpu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDatabaseLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereEggId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereIo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereMemory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereOomKiller($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereSkipScripts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereStartup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereSwap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereThreads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereuuid_short($value)
 *
 * @property string[]|null $docker_labels
 * @property string|null $ports
 * @property-read ContainerStatus|ServerState $condition
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EggVariable> $eggVariables
 * @property-read int|null $egg_variables_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServerVariable> $serverVariables
 * @property-read int|null $server_variables_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereDockerLabels($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereInstalledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server wherePorts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Server whereUuidShort($value)
 */
class Server extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;
    use Notifiable;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'server';

    /**
     * Default values when creating the model. We want to switch to disabling OOM killer
     * on server instances unless the user specifies otherwise in the request.
     */
    protected $attributes = [
        'status' => ServerState::Installing,
        'oom_killer' => false,
        'installed_at' => null,
    ];

    /**
     * The default relationships to load for all server models.
     */
    protected $with = ['allocation'];

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT, 'installed_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'external_id' => ['sometimes', 'nullable', 'string', 'between:1,255', 'unique:servers'],
        'owner_id' => ['required', 'integer', 'exists:users,id'],
        'name' => ['required', 'string', 'min:1', 'max:255'],
        'node_id' => ['required', 'exists:nodes,id'],
        'description' => ['string'],
        'status' => ['nullable', 'string'],
        'memory' => ['required', 'numeric', 'min:0'],
        'swap' => ['required', 'numeric', 'min:-1'],
        'io' => ['required', 'numeric', 'between:0,1000'],
        'cpu' => ['required', 'numeric', 'min:0'],
        'threads' => ['nullable', 'regex:/^[0-9-,]+$/'],
        'oom_killer' => ['sometimes', 'boolean'],
        'disk' => ['required', 'numeric', 'min:0'],
        'allocation_id' => ['required', 'bail', 'unique:servers', 'exists:allocations,id'],
        'egg_id' => ['required', 'exists:eggs,id'],
        'startup' => ['required', 'string'],
        'skip_scripts' => ['sometimes', 'boolean'],
        'image' => ['required', 'string', 'max:255'],
        'database_limit' => ['present', 'nullable', 'integer', 'min:0'],
        'allocation_limit' => ['sometimes', 'nullable', 'integer', 'min:0'],
        'backup_limit' => ['present', 'nullable', 'integer', 'min:0'],
    ];

    protected function casts(): array
    {
        return [
            'node_id' => 'integer',
            'status' => ServerState::class,
            'skip_scripts' => 'boolean',
            'owner_id' => 'integer',
            'memory' => 'integer',
            'swap' => 'integer',
            'disk' => 'integer',
            'io' => 'integer',
            'cpu' => 'integer',
            'oom_killer' => 'boolean',
            'allocation_id' => 'integer',
            'egg_id' => 'integer',
            'database_limit' => 'integer',
            'allocation_limit' => 'integer',
            'backup_limit' => 'integer',
            self::CREATED_AT => 'datetime',
            self::UPDATED_AT => 'datetime',
            'installed_at' => 'datetime',
            'docker_labels' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $server) {
            $subuser = $server->subusers()->where('user_id', $server->owner_id)->first();
            if ($subuser) {
                // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
                app(SubuserDeletionService::class)->handle($subuser, $server);
            }
        });
    }

    /**
     * Returns the format for server allocations when communicating with the Daemon.
     *
     * @return array<int>
     */
    public function getAllocationMappings(): array
    {
        return $this->allocations->where('node_id', $this->node_id)->groupBy('ip')->map(function ($item) {
            return $item->pluck('port');
        })->toArray();
    }

    public function isInstalled(): bool
    {
        return $this->status !== ServerState::Installing && !$this->isFailedInstall();
    }

    public function isFailedInstall(): bool
    {
        return $this->status === ServerState::InstallFailed || $this->status === ServerState::ReinstallFailed;
    }

    public function isSuspended(): bool
    {
        return $this->status === ServerState::Suspended;
    }

    /**
     * Gets the user who owns the server.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Gets the subusers associated with a server.
     *
     * @return HasMany<Subuser, $this>
     */
    public function subusers(): HasMany
    {
        return $this->hasMany(Subuser::class, 'server_id', 'id');
    }

    /**
     * Gets the default allocation for a server.
     */
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class);
    }

    /**
     * Gets all allocations associated with this server.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    /**
     * Gets information for the egg associated with this server.
     */
    public function egg(): HasOne
    {
        return $this->hasOne(Egg::class, 'id', 'egg_id');
    }

    public function eggVariables(): HasMany
    {
        return $this->hasMany(EggVariable::class, 'egg_id', 'egg_id');
    }

    /**
     * Gets information for the egg variables associated with this server.
     *
     * @return HasMany<EggVariable, $this>
     */
    public function variables(): HasMany
    {
        return $this->hasMany(EggVariable::class, 'egg_id', 'egg_id')
            ->select(['egg_variables.*', 'server_variables.variable_value as server_value'])
            ->leftJoin('server_variables', function (JoinClause $join) {
                // Don't forget to join against the server ID as well since the way we're using this relationship
                // would actually return all the variables and their values for _all_ servers using that egg,
                // rather than only the server for this model.
                $join->on('server_variables.variable_id', 'egg_variables.id')
                    ->where('server_variables.server_id', $this->id);
            });
    }

    public function serverVariables(): HasMany
    {
        return $this->hasMany(ServerVariable::class);
    }

    /**
     * Gets information for the node associated with this server.
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }

    /**
     * Gets information for the tasks associated with this server.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Gets all databases associated with a server.
     */
    public function databases(): HasMany
    {
        return $this->hasMany(Database::class);
    }

    /**
     * Returns the associated server transfer.
     */
    public function transfer(): HasOne
    {
        return $this->hasOne(ServerTransfer::class)->whereNull('successful')->orderByDesc('id');
    }

    /**
     * @return HasMany<Backup, $this>
     */
    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }

    public function mounts(): MorphToMany
    {
        return $this->morphToMany(Mount::class, 'mountable');
    }

    /**
     * Returns all the activity log entries where the server is the subject.
     */
    public function activity(): MorphToMany
    {
        return $this->morphToMany(ActivityLog::class, 'subject', 'activity_log_subjects');
    }

    public function resolveRouteBinding($value, $field = null): ?self
    {
        return match ($field) {
            'uuid' => $this->where('uuid_short', $value)->orWhere('uuid', $value)->firstOrFail(),
            default => $this->where('id', $value)->firstOrFail(),
        };
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        return match ($childType) {
            'user' => $this->subusers()->whereRelation('user', 'uuid', $value)->firstOrFail()->user,
            default => parent::resolveChildRouteBinding($childType, $value, $field),
        };
    }

    public function isInConflictState(): bool
    {
        return $this->isSuspended() || $this->node->isUnderMaintenance() || !$this->isInstalled() || $this->status === ServerState::RestoringBackup || !is_null($this->transfer);
    }

    /**
     * Checks if the server is currently in a user-accessible state. If not, an
     * exception is raised. This should be called whenever something needs to make
     * sure the server is not in a weird state that should block user access.
     *
     * @throws ServerStateConflictException
     */
    public function validateCurrentState(): void
    {
        if ($this->isInConflictState()) {
            throw new ServerStateConflictException($this);
        }
    }

    /**
     * Checks if the server is currently in a transferable state. If not, an
     * exception is raised. This should be called whenever something needs to make
     * sure the server can be transferred and is not currently being transferred
     * or installed.
     */
    public function validateTransferState(): void
    {
        if (
            !$this->isInstalled() ||
            $this->status === ServerState::RestoringBackup ||
            !is_null($this->transfer)
        ) {
            throw new ServerStateConflictException($this);
        }
    }

    /**
     * Sends a command or multiple commands to a running server instance.
     *
     * @param  string[]|string  $command
     *
     * @throws ConnectionException
     */
    public function send(array|string $command): ResponseInterface
    {
        return Http::daemon($this->node)->post("/api/servers/{$this->uuid}/commands", [
            'commands' => is_array($command) ? $command : [$command],
        ])->toPsrResponse();
    }

    public function retrieveStatus(): ContainerStatus
    {
        $status = cache()->get("servers.$this->uuid.container.status");

        if ($status === null) {
            $this->node->serverStatuses();

            $status = cache()->get("servers.$this->uuid.container.status");
        }

        return ContainerStatus::tryFrom($status) ?? ContainerStatus::Missing;
    }

    /**
     * @return array<mixed>
     */
    public function resources(): array
    {
        return cache()->remember("resources:$this->uuid", now()->addSeconds(15), function () {
            // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions
            return Arr::get(app(DaemonServerRepository::class)->setServer($this)->getDetails(), 'utilization', []);
        });
    }

    public function formatResource(string $resourceKey, bool $limit = false, ServerResourceType $type = ServerResourceType::Unit, int $precision = 2): string
    {
        $resourceAmount = $this->{$resourceKey} ?? 0;
        if (!$limit) {
            $resourceAmount = $this->resources()[$resourceKey] ?? 0;
        }

        if ($type === ServerResourceType::Time) {
            if ($this->isSuspended()) {
                return 'Suspended';
            }
            if ($resourceAmount === 0) {
                return ContainerStatus::Offline->getLabel();
            }

            return now()->subMillis($resourceAmount)->diffForHumans(syntax: CarbonInterface::DIFF_ABSOLUTE, short: true, parts: 4);
        }

        if ($resourceAmount === 0 & $limit) {
            return "\u{221E}";
        }

        if ($type === ServerResourceType::Percentage) {
            return Number::format($resourceAmount, precision: $precision, locale: auth()->user()->language ?? 'en') . '%';
        }

        // Our current limits are set in MB
        if ($limit) {
            $resourceAmount *= 2 ** 20;
        }

        return convert_bytes_to_readable($resourceAmount, decimals: $precision, base: 3);
    }

    public function condition(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->isSuspended() ? ServerState::Suspended : $this->status ?? $this->retrieveStatus(),
        );
    }
}
