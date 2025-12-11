<?php

namespace App\Policies;

use App\Enums\SubuserPermission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class BackupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(SubuserPermission::BackupRead, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::BackupRead, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(SubuserPermission::BackupCreate, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::BackupDelete, Filament::getTenant());
    }
}
