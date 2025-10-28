<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class SchedulePolicy
{
    protected string $modelName = 'schedule';

    public function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_SCHEDULE_READ, Filament::getTenant());
    }

    public function create(): bool
    {
        return user()?->can(Permission::ACTION_SCHEDULE_CREATE, Filament::getTenant());
    }

    public function edit(Model $record): bool
    {
        return user()?->can(Permission::ACTION_SCHEDULE_UPDATE, Filament::getTenant());
    }

    public function delete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_SCHEDULE_DELETE, Filament::getTenant());
    }
}
