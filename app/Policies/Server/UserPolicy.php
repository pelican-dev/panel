<?php

namespace App\Policies\Server;

use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ACTION_USER_READ, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_USER_READ, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::ACTION_USER_CREATE, Filament::getTenant());
    }

    public function edit(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_USER_UPDATE, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_USER_DELETE, Filament::getTenant());
    }
}
