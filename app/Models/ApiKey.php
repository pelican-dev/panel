<?php

namespace App\Models;

use App\Services\Acl\Api\AdminAcl;
use App\Traits\HasValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Webmozart\Assert\Assert;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ApiKey.
 *
 * @property int $id
 * @property int $user_id
 * @property int $key_type
 * @property string $identifier
 * @property string $token
 * @property string[]|null $permissions
 * @property string[]|null $allowed_ips
 * @property string|null $memo
 * @property \Illuminate\Support\Carbon|null $last_used_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\User $tokenable
 * @property \App\Models\User $user
 *
 * @method static \Database\Factories\ApiKeyFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereAllowedIps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereKeyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereLastUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereRAllocations($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereRDatabaseHosts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereREggs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereRNodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereRServerDatabases($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereRServers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereRUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereUserId($value)
 */
class ApiKey extends PersonalAccessToken
{
    use HasFactory;
    use HasValidation;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'api_key';

    /**
     * Different API keys that can exist on the system.
     */
    public const TYPE_NONE = 0;

    public const TYPE_ACCOUNT = 1;

    public const TYPE_APPLICATION = 2;

    /**
     * The length of API key identifiers.
     */
    public const IDENTIFIER_LENGTH = 16;

    /**
     * The length of the actual API key that is encrypted and stored
     * in the database.
     */
    public const KEY_LENGTH = 32;

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'key_type',
        'identifier',
        'token',
        'permissions',
        'allowed_ips',
        'memo',
        'last_used_at',
        'expires_at',
    ];

    /**
     * Default attributes when creating a new model.
     */
    protected $attributes = [
        'allowed_ips' => '[]',
        'permissions' => '[]',
    ];

    /**
     * Fields that should not be included when calling toArray() or toJson()
     * on this model.
     */
    protected $hidden = ['token'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'user_id' => ['required', 'exists:users,id'],
        'key_type' => ['present', 'integer', 'min:0', 'max:2'],
        'identifier' => ['required', 'string', 'size:16', 'unique:api_keys,identifier'],
        'token' => ['required', 'string'],
        'permissions' => ['array'],
        'permissions.*' => ['integer', 'min:0', 'max:3'],
        'memo' => ['required', 'nullable', 'string', 'max:500'],
        'allowed_ips' => ['array'],
        'allowed_ips.*' => ['string'],
        'last_used_at' => ['nullable', 'date'],
        'expires_at' => ['nullable', 'date'],
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'allowed_ips' => 'array',
            'user_id' => 'int',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'token' => 'encrypted',
            self::CREATED_AT => 'datetime',
            self::UPDATED_AT => 'datetime',
        ];
    }

    /**
     * Returns the user this token is assigned to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tokenable()
    {
        // @phpstan-ignore return.type
        return $this->user();
    }

    /**
     * Returns the permission for the given resource.
     */
    public function getPermission(string $resource): int
    {
        return $this->permissions[$resource] ?? AdminAcl::NONE;
    }

    public const DEFAULT_RESOURCE_NAMES = [
        Server::RESOURCE_NAME,
        Node::RESOURCE_NAME,
        Allocation::RESOURCE_NAME,
        User::RESOURCE_NAME,
        Egg::RESOURCE_NAME,
        DatabaseHost::RESOURCE_NAME,
        Database::RESOURCE_NAME,
        Mount::RESOURCE_NAME,
        Role::RESOURCE_NAME,
    ];

    /** @var string[] */
    protected static array $customResourceNames = [];

    public static function registerCustomResourceName(string $resourceName): void
    {
        static::$customResourceNames[] = $resourceName;
    }

    /**
     * Returns a list of all possible permission keys.
     *
     * @return string[]
     */
    public static function getPermissionList(): array
    {
        return array_unique(array_merge(self::DEFAULT_RESOURCE_NAMES, self::$customResourceNames));
    }

    /**
     * Finds the model matching the provided token.
     *
     * @param  string  $token
     */
    public static function findToken($token): ?self
    {
        $identifier = substr($token, 0, self::IDENTIFIER_LENGTH);

        /** @var static|null $model */
        $model = static::where('identifier', $identifier)->first();
        if (!is_null($model) && $model->token === substr($token, strlen($identifier))) {
            return $model;
        }

        return null;
    }

    /**
     * Returns the standard prefix for API keys in the system.
     */
    public static function getPrefixForType(int $type): string
    {
        Assert::oneOf($type, [self::TYPE_ACCOUNT, self::TYPE_APPLICATION]);

        return $type === self::TYPE_ACCOUNT ? 'plcn_' : 'peli_';
    }

    /**
     * Generates a new identifier for an API key.
     */
    public static function generateTokenIdentifier(int $type): string
    {
        $prefix = self::getPrefixForType($type);

        return $prefix . Str::random(self::IDENTIFIER_LENGTH - strlen($prefix));
    }
}
