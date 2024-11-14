<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $host
 * @property int $port
 * @property string $username
 * @property string $password
 * @property int|null $max_databases
 * @property int|null $node_id
 * @property \Carbon\CarbonImmutable $created_at
 * @property \Carbon\CarbonImmutable $updated_at
 */
class DatabaseHost extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'database_host';

    /**
     * The table associated with the model.
     */
    protected $table = 'database_hosts';

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = ['password'];

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'name', 'host', 'port', 'username', 'password', 'max_databases',
    ];

    /**
     * Validation rules to assign to this model.
     */
    public static array $validationRules = [
        'name' => 'required|string|max:255',
        'host' => 'required|string',
        'port' => 'required|numeric|between:1,65535',
        'username' => 'required|string|max:32',
        'password' => 'nullable|string',
        'node_ids' => 'nullable|array',
        'node_ids.*' => 'required|integer,exists:nodes,id',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'max_databases' => 'integer',
            'password' => 'encrypted',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function nodes(): BelongsToMany
    {
        return $this->belongsToMany(Node::class);
    }

    /**
     * Gets the databases associated with this host.
     */
    public function databases(): HasMany
    {
        return $this->hasMany(Database::class);
    }
}
