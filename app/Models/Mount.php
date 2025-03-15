<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Model;
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
class Mount extends Model implements Validatable
{
    use HasValidation { getRules as getValidationRules; }

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'mount';

    /**
     * Fields that are not mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Rules verifying that the data being stored matches the expectations of the database.
     *
     * @var array<array-key, string[]>
     */
    public static array $validationRules = [
        'name' => ['required', 'string', 'min:2', 'max:64', 'unique:mounts,name'],
        'description' => ['nullable', 'string', 'max:255'],
        'source' => ['required', 'string'],
        'target' => ['required', 'string'],
        'read_only' => ['sometimes', 'boolean'],
        'user_mountable' => ['sometimes', 'boolean'],
    ];

    /**
     * Implement language verification by overriding Eloquence's gather rules function.
     *
     * @return array<array-key, string[]>
     */
    public static function getRules(): array
    {
        $rules = self::getValidationRules();
        $rules['source'][] = 'not_in:' . implode(',', Mount::$invalidSourcePaths);
        $rules['target'][] = 'not_in:' . implode(',', Mount::$invalidTargetPaths);

        return $rules;
    }

    /**
     * Disable timestamps on this model.
     */
    public $timestamps = false;

    /**
     * Blacklisted source paths.
     *
     * @var string[]
     */
    public static array $invalidSourcePaths = [
        '/etc/pelican',
        '/var/lib/pelican/volumes',
        '/srv/daemon-data',
    ];

    /**
     * Blacklisted target paths.
     *
     * @var string[]
     */
    public static array $invalidTargetPaths = [
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
}
