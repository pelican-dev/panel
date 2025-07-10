<?php

namespace App\Models;

use App\Exceptions\Service\Allocation\ServerUsingAllocationException;
use App\Exceptions\Service\Allocation\PortConflictOnSameNetworkException;
use App\Traits\HasValidation;
use App\Rules\UniquePortOnSameNetwork;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use IPTools\Network;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $alias
 * @property bool $has_alias
 * @property string $address
 * @property \App\Models\Server|null $server
 * @property \App\Models\Node $node
 *
 * @method static \Database\Factories\AllocationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereIpAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereServerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Allocation whereUpdatedAt($value)
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
    ];

    /**
     * Returns the rules associated with the model, specifically for creating or updating.
     *
     * @return array<array-key, string[]|ValidationRule[]>
     */
    public static function getRules(): array
    {
        $rules = self::getValidationRules();
        
        // Add the custom validation rule for port conflicts on the same network
        $rules['port'][] = new UniquePortOnSameNetwork();
        
        return $rules;
    }

    /**
     * Returns the rules associated with the model, specifically for updating the given model.
     *
     * @return array<array-key, string[]|ValidationRule[]>
     */
    public static function getRulesForUpdate(self $model): array
    {
        $rules = self::getValidationRules();
        
        // For updates, we need to pass the current model ID to ignore it in validation
        $rules['port'][] = new UniquePortOnSameNetwork($model->id);
        
        return $rules;
    }

    protected static function booted(): void
    {
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

    /**
     * Check if there's a port conflict with another allocation on the same network.
     * This method determines if nodes are on the same network by checking if their
     * IP addresses fall within the same subnet.
     *
     * @param string $ip The IP address to check
     * @param int $port The port to check
     * @param int $nodeId The node ID to exclude from the check (current node)
     * @return bool True if there's a conflict, false otherwise
     */
    public static function hasPortConflictOnSameNetwork(string $ip, int $port, int $nodeId): bool
    {
        // Get all allocations with the same port
        $conflictingAllocations = static::query()
            ->where('port', $port)
            ->where('node_id', '!=', $nodeId)
            ->get();

        foreach ($conflictingAllocations as $allocation) {
            // Check if the IPs are on the same network by comparing their subnets
            if (static::areIpsOnSameNetwork($ip, $allocation->ip)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the conflicting allocation details for a port conflict on the same network.
     *
     * @param string $ip The IP address to check
     * @param int $port The port to check
     * @param int $nodeId The node ID to exclude from the check (current node)
     * @return \App\Models\Allocation|null The conflicting allocation or null if no conflict
     */
    public static function getPortConflictOnSameNetwork(string $ip, int $port, int $nodeId): ?self
    {
        $conflictingAllocations = static::query()
            ->where('port', $port)
            ->where('node_id', '!=', $nodeId)
            ->get();

        foreach ($conflictingAllocations as $allocation) {
            if (static::areIpsOnSameNetwork($ip, $allocation->ip)) {
                return $allocation;
            }
        }

        return null;
    }

    /**
     * Determine if two IP addresses are on the same network by checking if they
     * fall within the same subnet. This method handles both IPv4 and IPv6 addresses.
     *
     * @param string $ip1 First IP address
     * @param string $ip2 Second IP address
     * @return bool True if the IPs are on the same network, false otherwise
     */
    public static function areIpsOnSameNetwork(string $ip1, string $ip2): bool
    {
        try {
            // For IPv4 addresses, we'll use a /24 subnet as a reasonable default
            // This can be adjusted based on your network configuration
            $network1 = Network::parse($ip1 . '/24');
            $network2 = Network::parse($ip2 . '/24');

            // Use loose comparison because getNetwork() returns objects
            return $network1->getNetwork() == $network2->getNetwork();
        } catch (\Exception $e) {
            // If we can't parse the IPs, assume they're not on the same network
            return false;
        }
    }
}
