<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class UserPolicy
{
    protected string $modelName = 'user';

    public static function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_USER_READ, Filament::getTenant());
    }

    public static function create(): bool
    {
        return user()?->can(Permission::ACTION_USER_CREATE, Filament::getTenant());
    }

    public static function edit(Model $record): bool
    {
        return user()?->can(Permission::ACTION_USER_UPDATE, Filament::getTenant());
    }

    public static function delete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_USER_DELETE, Filament::getTenant());
    }
}
