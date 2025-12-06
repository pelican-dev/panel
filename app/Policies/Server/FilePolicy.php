<?php

namespace App\Policies\Server;

use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class FilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ACTION_FILE_READ, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_FILE_READ_CONTENT, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::ACTION_FILE_CREATE, Filament::getTenant());
    }

    public function edit(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_FILE_UPDATE, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(Permission::ACTION_FILE_DELETE, Filament::getTenant());
    }
}
