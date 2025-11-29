<?php

namespace App\Models;

use App\Enums\RolePermissionModels;
use App\Enums\RolePermissionPrefixes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Collection|Permission[] $permissions
 * @property int|null $permissions_count
 * @property Collection|User[] $users
 * @property int|null $users_count
 * @property Collection|Node[] $nodes
 * @property int|null $nodes_count
 */
class Role extends BaseRole
{
    use HasFactory;

    public const RESOURCE_NAME = 'role';

    public const ROOT_ADMIN = 'Root Admin';

    public const DEFAULT_GUARD_NAME = 'web';

    public const MODEL_SPECIFIC_PERMISSIONS = [
        'egg' => [
            'import',
            'export',
        ],
    ];

    public const SPECIAL_PERMISSIONS = [
        'settings' => [
            'view',
            'update',
        ],
        'health' => [
            'view',
        ],
        'activityLog' => [
            'seeIps',
        ],
        'panelLog' => [
            'view',
        ],
        'plugin' => [
            'viewList',
            'create',
            'update',
            'delete',
        ],
    ];

    public const MODEL_ICONS = [
        'health' => 'tabler-heart',
        'activityLog' => 'tabler-stack',
        'panelLog' => 'tabler-file-info',
    ];

    /** @var array<string, array<string>> */
    protected static array $customPermissions = [];

    /** @param array<string, array<string>> $customPermissions */
    public static function registerCustomPermissions(array $customPermissions): void
    {
        static::$customPermissions = [
            ...static::$customPermissions,
            ...$customPermissions,
        ];
    }

    public static function registerCustomDefaultPermissions(string $model): void
    {
        $permissions = [];

        foreach (RolePermissionPrefixes::cases() as $prefix) {
            $permissions[] = $prefix->value;
        }

        static::registerCustomPermissions([
            $model => $permissions,
        ]);
    }

    /** @var array<string, string> */
    protected static array $customModelIcons = [];

    public static function registerCustomModelIcon(string $model, string $icon): void
    {
        static::$customModelIcons[$model] = $icon;
    }

    /** @return array<string, array<string>> */
    public static function getPermissionList(): array
    {
        $allPermissions = [];

        // Standard permissions for our default model
        foreach (RolePermissionModels::cases() as $model) {
            $allPermissions[$model->value] ??= [];

            foreach (RolePermissionPrefixes::cases() as $prefix) {
                array_push($allPermissions[$model->value], $prefix->value);
            }

            if (array_key_exists($model->value, Role::MODEL_SPECIFIC_PERMISSIONS)) {
                foreach (static::MODEL_SPECIFIC_PERMISSIONS[$model->value] as $permission) {
                    array_push($allPermissions[$model->value], $permission);
                }
            }
        }

        // Special permissions for our default models
        foreach (static::SPECIAL_PERMISSIONS as $model => $prefixes) {
            $allPermissions[$model] ??= [];

            foreach ($prefixes as $prefix) {
                array_push($allPermissions[$model], $prefix);
            }
        }

        // Custom third party permissions
        foreach (static::$customPermissions as $model => $prefixes) {
            $allPermissions[$model] ??= [];

            foreach ($prefixes as $prefix) {
                array_push($allPermissions[$model], $prefix);
            }
        }

        foreach ($allPermissions as $model => $permissions) {
            $allPermissions[$model] = array_unique($permissions);
        }

        return $allPermissions;
    }

    public static function getModelIcon(string $model): ?string
    {
        $customModels = array_merge(static::MODEL_ICONS, static::$customModelIcons);

        if (array_key_exists($model, $customModels)) {
            return $customModels[$model];
        }

        $model = ucwords($model);

        if (class_exists($class = '\\App\\Filament\\Admin\\Resources\\' . $model . 's\\' . $model . 'Resource')) {
            return $class::getNavigationIcon();
        }

        if (class_exists($class = '\\App\\Filament\\Admin\\Pages\\' . $model)) {
            return $class::getNavigationIcon();
        }

        if (class_exists($class = '\\App\\Filament\\Server\\Resources\\' . $model . 's\\' . $model . 'Resource')) {
            return $class::getNavigationIcon();
        }

        return null;
    }

    public function isRootAdmin(): bool
    {
        return $this->name === self::ROOT_ADMIN;
    }

    public static function getRootAdmin(): self
    {
        /** @var self $role */
        $role = self::findOrCreate(self::ROOT_ADMIN, self::DEFAULT_GUARD_NAME);

        return $role;
    }

    public function nodes(): BelongsToMany
    {
        return $this->belongsToMany(Node::class, NodeRole::class);
    }
}
