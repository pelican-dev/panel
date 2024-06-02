<?php

namespace App\Models;

use Illuminate\Support\Str;
use Webmozart\Assert\Assert;
use App\Services\Acl\Api\AdminAcl;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKey extends Model
{
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
    /* @deprecated */
    public const TYPE_APPLICATION = 2;
    /* @deprecated */
    public const TYPE_DAEMON_USER = 3;
    /* @deprecated */
    public const TYPE_DAEMON_APPLICATION = 4;
    /**
     * The length of API key identifiers.
     */
    public const IDENTIFIER_LENGTH = 16;
    /**
     * The length of the actual API key that is encrypted and stored
     * in the database.
     */
    public const KEY_LENGTH = 32;

    public const RESOURCES = ['servers', 'nodes', 'allocations', 'users', 'eggs', 'database_hosts', 'server_databases', 'mounts'];

    /**
     * The table associated with the model.
     */
    protected $table = 'api_keys';

    /**
     * Fields that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'key_type',
        'identifier',
        'token',
        'allowed_ips',
        'memo',
        'last_used_at',
        'expires_at',
        'r_' . AdminAcl::RESOURCE_USERS,
        'r_' . AdminAcl::RESOURCE_ALLOCATIONS,
        'r_' . AdminAcl::RESOURCE_DATABASE_HOSTS,
        'r_' . AdminAcl::RESOURCE_SERVER_DATABASES,
        'r_' . AdminAcl::RESOURCE_EGGS,
        'r_' . AdminAcl::RESOURCE_NODES,
        'r_' . AdminAcl::RESOURCE_SERVERS,
        'r_' . AdminAcl::RESOURCE_MOUNTS,
    ];

    /**
     * Fields that should not be included when calling toArray() or toJson()
     * on this model.
     */
    protected $hidden = ['token'];

    /**
     * Rules to protect against invalid data entry to DB.
     */
    public static array $validationRules = [
        'user_id' => 'required|exists:users,id',
        'key_type' => 'present|integer|min:0|max:4',
        'identifier' => 'required|string|size:16|unique:api_keys,identifier',
        'token' => 'required|string',
        'memo' => 'required|nullable|string|max:500',
        'allowed_ips' => 'nullable|array',
        'allowed_ips.*' => 'string',
        'last_used_at' => 'nullable|date',
        'expires_at' => 'nullable|date',
        'r_' . AdminAcl::RESOURCE_USERS => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_ALLOCATIONS => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_DATABASE_HOSTS => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_SERVER_DATABASES => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_EGGS => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_NODES => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_SERVERS => 'integer|min:0|max:3',
        'r_' . AdminAcl::RESOURCE_MOUNTS => 'integer|min:0|max:3',
    ];

    protected function casts(): array
    {
        return [
            'allowed_ips' => 'array',
            'user_id' => 'int',
            'last_used_at' => 'datetime',
            'expires_at' => 'datetime',
            'token' => 'encrypted',
            self::CREATED_AT => 'datetime',
            self::UPDATED_AT => 'datetime',
            'r_' . AdminAcl::RESOURCE_USERS => 'int',
            'r_' . AdminAcl::RESOURCE_ALLOCATIONS => 'int',
            'r_' . AdminAcl::RESOURCE_DATABASE_HOSTS => 'int',
            'r_' . AdminAcl::RESOURCE_SERVER_DATABASES => 'int',
            'r_' . AdminAcl::RESOURCE_EGGS => 'int',
            'r_' . AdminAcl::RESOURCE_NODES => 'int',
            'r_' . AdminAcl::RESOURCE_SERVERS => 'int',
            'r_' . AdminAcl::RESOURCE_MOUNTS => 'int',
        ];
    }

    /**
     * Returns the user this token is assigned to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Required for support with Laravel Sanctum.
     *
     * @see \Laravel\Sanctum\Guard::supportsTokens()
     */
    public function tokenable(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Finds the model matching the provided token.
     */
    public static function findToken(string $token): ?self
    {
        $identifier = substr($token, 0, self::IDENTIFIER_LENGTH);

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
