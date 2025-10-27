<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class FilePolicy
{
    protected string $modelName = 'file';

    public function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_FILE_READ, Filament::getTenant());
    }

    public function create(): bool
    {
        return user()?->can(Permission::ACTION_FILE_CREATE, Filament::getTenant());
    }

    public function edit(Model $record): bool
    {
        return user()?->can(Permission::ACTION_FILE_UPDATE, Filament::getTenant());
    }

    public function delete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_FILE_DELETE, Filament::getTenant());
    }
}
