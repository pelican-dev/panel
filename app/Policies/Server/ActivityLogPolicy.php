<?php

namespace App\Policies\Server;

use App\Models\Permission;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }

    public function view(User $user, Model $model): bool
    {
        return $user->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }
}
