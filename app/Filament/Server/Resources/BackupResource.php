<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\BackupResource\Pages;
use App\Models\Backup;
use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'tabler-file-zip';

    protected static bool $canCreateAnother = false;

    public static function getNavigationBadge(): ?string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->backup_limit === 0) {
            return null;
        }

        return $server->backups->count() . ' / ' . $server->backup_limit;
    }

    public static function getNavigationBadgeColor(): string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return $server->backups->count() >= $server->backup_limit ? 'danger'
            : ($server->backups->count() >= $server->backup_limit * 0.7 ? 'warning' : 'success');
    }

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_BACKUP_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_BACKUP_CREATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_BACKUP_DELETE, Filament::getTenant());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
        ];
    }
}
