<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class ActivityLogPolicy
{
    protected string $modelName = 'activityLog';

    public function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }

    public function view(Model $model): bool
    {
        return user()?->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }
}
