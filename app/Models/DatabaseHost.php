<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Carbon\CarbonImmutable;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $host
 * @property int $port
 * @property string $username
 * @property string $password
 * @property int|null $max_databases
 * @property int|null $node_id
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property Collection|Node[] $nodes
 * @property int|null $nodes_count
 * @property Collection|Database[] $databases
 * @property int|null $databases_count
 */
class DatabaseHost extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal. Also used as name for api key permissions.
     */
    public const RESOURCE_NAME = 'database_host';

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

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'name' => ['required', 'string', 'max:255'],
        'host' => ['required', 'string'],
        'port' => ['required', 'numeric', 'between:1,65535'],
        'username' => ['required', 'string', 'max:32'],
        'password' => ['nullable', 'string'],
        'node_ids' => ['nullable', 'array'],
        'node_ids.*' => ['required', 'integer', 'exists:nodes,id'],
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

    public function buildConnection(string $database = 'mysql', string $charset = 'utf8', string $collation = 'utf8_unicode_ci'): Connection
    {
        /** @var Connection $connection */
        $connection = DB::build([
            'driver' => 'mysql',
            'host' => $this->host,
            'port' => $this->port,
            'database' => $database,
            'username' => $this->username,
            'password' => $this->password,
            'charset' => $charset,
            'collation' => $collation,
        ]);

        return $connection;
    }
}
