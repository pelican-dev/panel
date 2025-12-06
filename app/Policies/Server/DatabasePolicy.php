<?php

namespace App\Policies\Server;

use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class DatabasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::ACTION_DATABASE_CREATE, Filament::getTenant());
    }

    public function edit(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_DATABASE_UPDATE, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_DATABASE_DELETE, Filament::getTenant());
    }
}
