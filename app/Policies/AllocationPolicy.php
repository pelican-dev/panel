<?php

namespace App\Policies;

use App\Enums\SubuserPermission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class AllocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(SubuserPermission::AllocationRead, Filament::getTenant());
    }

    public function view(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::AllocationRead, Filament::getTenant());
    }

    public function create(User $user): bool
    {
        return $user->can(SubuserPermission::AllocationCreate, Filament::getTenant());
    }

    public function edit(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::AllocationUpdate, Filament::getTenant());
    }

    public function delete(User $user, Model $record): bool
    {
        return $user->can(SubuserPermission::AllocationDelete, Filament::getTenant());
    }
}
