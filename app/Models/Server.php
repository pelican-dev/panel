<?php

namespace App\Models;

use App\Enums\ServerState;
use App\Exceptions\Http\Connection\DaemonConnectionException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Exceptions\Http\Server\ServerStateConflictException;

class Server extends Model
{
    use Notifiable;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'server';

    /**
     * The table associated with the model.
     */
    protected $table = 'servers';

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
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', self::CREATED_AT, self::UPDATED_AT, 'deleted_at', 'installed_at'];

    public static array $validationRules = [
        'external_id' => 'sometimes|nullable|string|between:1,191|unique:servers',
        'owner_id' => 'required|integer|exists:users,id',
        'name' => 'required|string|min:1|max:191',
        'node_id' => 'required|exists:nodes,id',
        'description' => 'string',
        'status' => 'nullable|string',
        'memory' => 'required|numeric|min:0',
        'swap' => 'required|numeric|min:-1',
        'io' => 'required|numeric|between:0,1000',
        'cpu' => 'required|numeric|min:0',
        'threads' => 'nullable|regex:/^[0-9-,]+$/',
        'oom_killer' => 'sometimes|boolean',
        'disk' => 'required|numeric|min:0',
        'egg_id' => 'required|exists:eggs,id',
        'startup' => 'required|string',
        'skip_scripts' => 'sometimes|boolean',
        'image' => 'required|string|max:191',
        'database_limit' => 'present|nullable|integer|min:0',
        'allocation_limit' => 'sometimes|nullable|integer|min:0',
        'backup_limit' => 'present|nullable|integer|min:0',
        'ports' => 'array',
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
            'egg_id' => 'integer',
            'database_limit' => 'integer',
            'allocation_limit' => 'integer',
            'backup_limit' => 'integer',
            'deleted_at' => 'datetime',
            'installed_at' => 'datetime',
            'docker_labels' => 'array',
            'ports' => 'array',
        ];
    }

    /**
     * Returns the format for server allocations when communicating with the Daemon.
     */
    public function getPortMappings(): array
    {
        $defaultIp = '0.0.0.0';

        $ports = collect($this->ports)
            ->map(fn ($port) => str_contains($port, ':') ? $port : "$defaultIp:$port")
            ->mapToGroups(function ($port) {
                [$ip, $port] = explode(':', $port);

                return [$ip => (int) $port];
            });

        return $ports->all();
    }

    public function isInstalled(): bool
    {
        return $this->status !== ServerState::Installing && $this->status !== ServerState::InstallFailed;
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
     */
    public function subusers(): HasMany
    {
        return $this->hasMany(Subuser::class, 'server_id', 'id');
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

    public function backups(): HasMany
    {
        return $this->hasMany(Backup::class);
    }

    public function mounts(): BelongsToMany
    {
        return $this->belongsToMany(Mount::class);
    }

    /**
     * Returns all the activity log entries where the server is the subject.
     */
    public function activity(): MorphToMany
    {
        return $this->morphToMany(ActivityLog::class, 'subject', 'activity_log_subjects');
    }

    public function getRouteKeyName(): string
    {
        return 'id';
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

    /**
     * Checks if the server is currently in a user-accessible state. If not, an
     * exception is raised. This should be called whenever something needs to make
     * sure the server is not in a weird state that should block user access.
     *
     * @throws ServerStateConflictException
     */
    public function validateCurrentState()
    {
        if (
            $this->isSuspended() ||
            $this->node->isUnderMaintenance() ||
            !$this->isInstalled() ||
            $this->status === ServerState::RestoringBackup ||
            !is_null($this->transfer)
        ) {
            throw new ServerStateConflictException($this);
        }
    }

    /**
     * Checks if the server is currently in a transferable state. If not, an
     * exception is raised. This should be called whenever something needs to make
     * sure the server can be transferred and is not currently being transferred
     * or installed.
     */
    public function validateTransferState()
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
     * @throws DaemonConnectionException|GuzzleException
     */
    public function send(array|string $command): ResponseInterface
    {
        try {
            return Http::daemon($this->node)->post("/api/servers/{$this->uuid}/commands", [
                'commands' => is_array($command) ? $command : [$command],
            ])->toPsrResponse();
        } catch (GuzzleException $exception) {
            throw new DaemonConnectionException($exception);
        }
    }

    public function retrieveStatus(): string
    {
        $status = cache()->get("servers.$this->uuid.container.status");

        if ($status) {
            return $status;
        }

        $this->node->serverStatuses();

        return cache()->get("servers.$this->uuid.container.status") ?? 'missing';
    }
}
