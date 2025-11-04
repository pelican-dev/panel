<?php

namespace App\Models;

use App\Exceptions\Service\Allocation\ServerUsingAllocationException;
use App\Traits\HasValidation;
use Carbon\Carbon;
use Database\Factories\AllocationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Allocation.
 *
 * @property int $id
 * @property int $node_id
 * @property string $ip
 * @property string|null $ip_alias
 * @property int $port
 * @property int|null $server_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $alias
 * @property bool $has_alias
 * @property string $address
 * @property Server|null $server
 * @property Node $node
 * @property bool $is_locked
 *
 * @method static AllocationFactory factory(...$parameters)
 * @method static Builder|Allocation newModelQuery()
 * @method static Builder|Allocation newQuery()
 * @method static Builder|Allocation query()
 * @method static Builder|Allocation whereCreatedAt($value)
 * @method static Builder|Allocation whereId($value)
 * @method static Builder|Allocation whereIp($value)
 * @method static Builder|Allocation whereIpAlias($value)
 * @method static Builder|Allocation whereNodeId($value)
 * @method static Builder|Allocation whereNotes($value)
 * @method static Builder|Allocation wherePort($value)
 * @method static Builder|Allocation whereServerId($value)
 * @method static Builder|Allocation whereUpdatedAt($value)
 */
class Allocation extends Model
{
    use HasFactory;
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'allocation';

    protected $attributes = [
        'is_locked' => false,
    ];

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'node_id' => ['required', 'exists:nodes,id'],
        'ip' => ['required', 'ip'],
        'port' => ['required', 'numeric', 'between:1024,65535'],
        'ip_alias' => ['nullable', 'string'],
        'server_id' => ['nullable', 'exists:servers,id'],
        'notes' => ['nullable', 'string', 'max:256'],
        'is_locked' => ['boolean'],
    ];

    protected static function booted(): void
    {
        static::updating(function (self $allocation) {
            if (is_null($allocation->server_id)) {
                $allocation->is_locked = false;
            }
        });

        static::deleting(function (self $allocation) {
            throw_if($allocation->server_id, new ServerUsingAllocationException(trans('exceptions.allocations.server_using')));
        });
    }

    protected function casts(): array
    {
        return [
            'node_id' => 'integer',
            'port' => 'integer',
            'server_id' => 'integer',
            'is_locked' => 'bool',
        ];
    }

    /**
     * Accessor to automatically provide the IP alias if defined.
     */
    public function getAliasAttribute(?string $value): string
    {
        return (is_null($this->ip_alias)) ? $this->ip : $this->ip_alias;
    }

    /**
     * Accessor to quickly determine if this allocation has an alias.
     */
    public function getHasAliasAttribute(?string $value): bool
    {
        return !is_null($this->ip_alias);
    }

    /** @return Attribute<string, never> */
    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn () => (is_ipv6($this->alias) ? "[$this->alias]" : $this->alias) . ":$this->port",
        );
    }

    /**
     * Gets information for the server associated with this allocation.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Return the Node model associated with this allocation.
     */
    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class);
    }
}
