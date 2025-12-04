<?php

namespace App\Models;

use App\Contracts\Validatable;
use App\Enums\SubuserPermission;
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

    /** @var array<string, array<string>> */
    protected static array $customPermissions = [];

    /** @param array<string, array<string>> $permissions */
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
        $defaultPermissions = [];

        foreach (SubuserPermission::cases() as $subuserPermission) {
            [$group, $permission] = $subuserPermission->split();

            $defaultPermissions[$group] = [
                'name' => $group,
                'hidden' => $subuserPermission->isHidden(),
                'icon' => $subuserPermission->getIcon(),
                'permissions' => array_merge($defaultPermissions[$group]['permissions'] ?? [], [$permission]),
            ];
        }

        $defaultPermissions = array_values($defaultPermissions);

        return array_merge($defaultPermissions, static::$customPermissions);
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

    public static function doesPermissionExist(string|SubuserPermission $permission): bool
    {
        if ($permission instanceof SubuserPermission) {
            $permission = $permission->value;
        }

        return str_contains($permission, '.') && in_array($permission, static::allPermissionKeys());
    }
}
