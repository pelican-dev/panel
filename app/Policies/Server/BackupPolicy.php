<?php

namespace App\Policies\Server;

use App\Models\Permission;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class BackupPolicy
{
    protected string $modelName = 'backup';

    public static function viewAny(): bool
    {
        return user()?->can(Permission::ACTION_BACKUP_READ, Filament::getTenant());
    }

    public static function create(): bool
    {
        return user()?->can(Permission::ACTION_BACKUP_CREATE, Filament::getTenant());
    }

    public static function delete(Model $record): bool
    {
        return user()?->can(Permission::ACTION_BACKUP_DELETE, Filament::getTenant());
    }
}
