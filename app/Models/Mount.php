<?php

namespace App\Models;

use Illuminate\Validation\Rules\NotIn;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $source
 * @property string $target
 * @property bool $read_only
 * @property bool $user_mountable
 * @property \App\Models\Egg[]|\Illuminate\Database\Eloquent\Collection $eggs
 * @property \App\Models\Node[]|\Illuminate\Database\Eloquent\Collection $nodes
 * @property \App\Models\Server[]|\Illuminate\Database\Eloquent\Collection $servers
 */
class Mount extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'mount';

    /**
     * The table associated with the model.
     */
    protected $table = 'mounts';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id'];

    /**
     * Rules verifying that the data being stored matches the expectations of the database.
     */
    public static array $validationRules = [
        'name' => 'required|string|min:2|max:64|unique:mounts,name',
        'description' => 'nullable|string|max:255',
        'source' => 'required|string',
        'target' => 'required|string',
        'read_only' => 'sometimes|boolean',
        'user_mountable' => 'sometimes|boolean',
    ];

    /**
     * Implement language verification by overriding Eloquence's gather
     * rules function.
     */
    public static function getRules(): array
    {
        $rules = parent::getRules();

        $rules['source'][] = new NotIn(Mount::$invalidSourcePaths);
        $rules['target'][] = new NotIn(Mount::$invalidTargetPaths);

        return $rules;
    }

    /**
     * Disable timestamps on this model.
     */
    public $timestamps = false;

    /**
     * Blacklisted source paths.
     */
    public static $invalidSourcePaths = [
        '/etc/pelican',
        '/var/lib/pelican/volumes',
        '/srv/daemon-data',
    ];

    /**
     * Blacklisted target paths.
     */
    public static $invalidTargetPaths = [
        '/home/container',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'int',
            'read_only' => 'bool',
            'user_mountable' => 'bool',
        ];
    }

    /**
     * Returns all eggs that have this mount assigned.
     */
    public function eggs(): BelongsToMany
    {
        return $this->belongsToMany(Egg::class);
    }

    /**
     * Returns all nodes that have this mount assigned.
     */
    public function nodes(): BelongsToMany
    {
        return $this->belongsToMany(Node::class);
    }

    /**
     * Returns all servers that have this mount assigned.
     */
    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
