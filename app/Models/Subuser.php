<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Traits\HasValidation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int $user_id
 * @property int $server_id
 * @property string[] $permissions
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property Server $server
 */
class Subuser extends Model implements Validatable
{
    use HasFactory;
    use HasValidation;
    use Notifiable;

    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'server_subuser';

    public const DEFAULT_PERMISSIONS = [
        [
            'name' => 'websocket',
            'hidden' => true,
            'permissions' => ['connect'],
        ],
        [
            'name' => 'control',
            'icon' => 'tabler-terminal-2',
            'permissions' => ['console', 'start', 'stop', 'restart'],
        ],
        [
            'name' => 'user',
            'icon' => 'tabler-users',
            'permissions' => ['read', 'create', 'update', 'delete'],
        ],
        [
            'name' => 'file',
            'icon' => 'tabler-files',
            'permissions' => ['read', 'read-content', 'create', 'update', 'delete', 'archive', 'sftp'],
        ],
        [
            'name' => 'backup',
            'icon' => 'tabler-file-zip',
            'permissions' => ['read', 'create', 'delete', 'download', 'restore'],
        ],
        [
            'name' => 'allocation',
            'icon' => 'tabler-network',
            'permissions' => ['read', 'create', 'update', 'delete'],
        ],
        [
            'name' => 'startup',
            'icon' => 'tabler-player-play',
            'permissions' => ['read', 'update', 'docker-image'],
        ],
        [
            'name' => 'database',
            'icon' => 'tabler-database',
            'permissions' => ['read', 'create', 'update', 'delete', 'view-password'],
        ],
        [
            'name' => 'schedule',
            'icon' => 'tabler-clock',
            'permissions' => ['read', 'create', 'update', 'delete'],
        ],
        [
            'name' => 'settings',
            'icon' => 'tabler-settings',
            'permissions' => ['rename', 'description', 'reinstall'],
        ],
        [
            'name' => 'activity',
            'icon' => 'tabler-stack',
            'permissions' => ['read'],
        ],
    ];

    /** @var array<string, array<string>> */
    protected static array $customPermissions = [];

    /** @param array<string, array<string>> $customPermissions */
    public static function registerCustomPermission(string $name, string $icon, array $permissions): void
    {
        array_push(static::$customPermissions, [
            'name' => $name,
            'icon' => $icon,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /** @var array<array-key, string[]> */
    public static array $validationRules = [
        'user_id' => ['required', 'numeric', 'exists:users,id'],
        'server_id' => ['required', 'numeric', 'exists:servers,id'],
        'permissions' => ['nullable', 'array'],
        'permissions.*' => ['string'],
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'int',
            'server_id' => 'int',
            'permissions' => 'array',
        ];
    }

    /**
     * Gets the server associated with a subuser.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Gets the user associated with a subuser.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return array<array<string, mixed>> */
    public static function allPermissionData(): array
    {
        return array_merge(static::DEFAULT_PERMISSIONS, static::$customPermissions);
    }

    /** @return string[] */
    public static function allPermissionKeys(): array
    {
        return collect(static::allPermissionData())
            ->map(fn ($data) => array_map(fn ($permission) => $data['name'] . '.' . $permission, $data['permissions']))
            ->flatten()
            ->unique()
            ->toArray();
    }

    public static function doesPermissionExist(string $permission): bool
    {
        return str_contains($permission, '.') && in_array($permission, static::allPermissionKeys());
    }
}
