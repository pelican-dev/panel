<?php

namespace App\Policies;

use App\Enums\SubuserPermission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class DatabasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(SubuserPermission::DatabaseRead, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::DatabaseRead, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(SubuserPermission::DatabaseCreate, Filament::getTenant());
    }

    public function edit(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::DatabaseUpdate, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::DatabaseDelete, Filament::getTenant());
    }
}
