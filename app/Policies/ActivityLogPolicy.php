<?php

namespace App\Policies;

use App\Enums\SubuserPermission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(SubuserPermission::ActivityRead, Filament::getTenant());
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can(SubuserPermission::ActivityRead, Filament::getTenant());
    }
}
