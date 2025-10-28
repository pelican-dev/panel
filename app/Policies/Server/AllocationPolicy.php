<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class AllocationPolicy
{
    protected string $modelName = 'allocation';

    public function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_ALLOCATION_READ, Filament::getTenant());
    }

    public function create(): bool
    {
        return user()?->can(Permission::ACTION_ALLOCATION_CREATE, Filament::getTenant());
    }

    public function edit(Model $record): bool
    {
        return user()?->can(Permission::ACTION_ALLOCATION_UPDATE, Filament::getTenant());
    }

    public function delete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_ALLOCATION_DELETE, Filament::getTenant());
    }
}
