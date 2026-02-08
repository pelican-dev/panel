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

    /** @var array<string, array{name: string, hidden: ?bool, icon: ?string, permissions: string[]}> */
    protected static array $customPermissions = [];

    /**
     * @param  string[]  $permissions
     * @param  ?string  $label  Custom label for the permission tab (overrides translation lookup)
     * @param  ?string  $description  Custom description for the permission group (overrides translation lookup)
     * @param  ?array<string, string>  $descriptions  Custom descriptions keyed by permission name (overrides translation lookup)
     * @param  ?string  $translationPrefix  Translation prefix for looking up labels/descriptions (e.g. 'my-plugin::permissions.')
     */
    public static function registerCustomPermissions(string $name, array $permissions, ?string $icon = null, ?bool $hidden = null, ?string $translationPrefix = null, ?string $label = null, ?string $description = null, ?array $descriptions = null): void
    {
        $customPermission = static::$customPermissions[$name] ?? [];

        $customPermission['name'] = $name;
        $customPermission['permissions'] = array_merge($customPermission['permissions'] ?? [], $permissions);

        if (!is_null($icon)) {
            $customPermission['icon'] = $icon;
        }

        if (!is_null($hidden)) {
            $customPermission['hidden'] = $hidden;
        }

        if (!is_null($translationPrefix)) {
            $customPermission['translationPrefix'] = $translationPrefix;
        }

        if (!is_null($label)) {
            $customPermission['label'] = $label;
        }

        if (!is_null($description)) {
            $customPermission['description'] = $description;
        }

        if (!is_null($descriptions)) {
            $customPermission['descriptions'] = array_merge($customPermission['descriptions'] ?? [], $descriptions);
        }

        static::$customPermissions[$name] = $customPermission;
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

    /** @return array<array{name: string, hidden: bool, icon: string, permissions: string[]}> */
    public static function allPermissionData(): array
    {
        $allPermissions = [];

        foreach (SubuserPermission::cases() as $subuserPermission) {
            [$group, $permission] = $subuserPermission->split();

            $allPermissions[$group] = [
                'name' => $group,
                'hidden' => $subuserPermission->isHidden(),
                'icon' => $subuserPermission->getIcon(),
                'permissions' => array_merge($allPermissions[$group]['permissions'] ?? [], [$permission]),
            ];
        }

        foreach (static::$customPermissions as $customPermission) {
            $name = $customPermission['name'];

            $groupData = $allPermissions[$name] ?? [];

            $groupData = [
                'name' => $name,
                'hidden' => $customPermission['hidden'] ?? $groupData['hidden'] ?? false,
                'icon' => $customPermission['icon'] ?? $groupData['icon'],
                'permissions' => array_unique(array_merge($groupData['permissions'] ?? [], $customPermission['permissions'])),
                'translationPrefix' => $customPermission['translationPrefix'] ?? $groupData['translationPrefix'] ?? null,
                'label' => $customPermission['label'] ?? $groupData['label'] ?? null,
                'description' => $customPermission['description'] ?? $groupData['description'] ?? null,
                'descriptions' => array_merge($groupData['descriptions'] ?? [], $customPermission['descriptions'] ?? []),
            ];

            $allPermissions[$name] = $groupData;
        }

        return array_values($allPermissions);
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
