<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class DatabasePolicy
{
    protected string $modelName = 'database';

    public static function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public static function view(Model $record): bool
    {
        return user()?->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public static function create(): bool
    {
        return user()?->can(Permission::ACTION_DATABASE_CREATE, Filament::getTenant());
    }

    public static function edit(Model $record): bool
    {
        return user()?->can(Permission::ACTION_DATABASE_UPDATE, Filament::getTenant());
    }

    public static function delete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_DATABASE_DELETE, Filament::getTenant());
    }
}
