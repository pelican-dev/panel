<?php

namespace App\Policies;

use App\Enums\SubuserPermission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class FilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(SubuserPermission::FileRead, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::FileReadContent, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(SubuserPermission::FileCreate, Filament::getTenant());
    }

    public function edit(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::FileUpdate, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::FileDelete, Filament::getTenant());
    }
}
