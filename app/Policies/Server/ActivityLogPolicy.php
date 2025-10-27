<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;

class ActivityLogPolicy
{
    protected string $modelName = 'activityLog';

    public static function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }

    public static function view(): bool
    {
        return user()?->can(Permission::ACTION_ACTIVITY_READ, Filament::getTenant());
    }
}
