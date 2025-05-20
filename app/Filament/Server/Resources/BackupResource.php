<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\BackupResource\Pages;
use App\Models\Backup;
use App\Models\Permission;
use App\Models\Server;
use App\Traits\Filament\BlockAccessInConflict;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class BackupResource extends Resource
{
    use BlockAccessInConflict;
    use CanCustomizePages;
    use CanCustomizeRelations;

    protected static ?string $model = Backup::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'tabler-file-zip';

    protected static bool $canCreateAnother = false;

    public const WARNING_THRESHOLD = 0.7;

    public static function getNavigationBadge(): string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $limit = $server->backup_limit;

        return $server->backups->count() . ($limit === 0 ? '' : ' / ' . $limit);
    }

    public static function getNavigationBadgeColor(): ?string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $limit = $server->backup_limit;
        $count = $server->backups->count();

        if ($limit === 0) {
            return null;
        }

        return $count >= $limit ? 'danger'
            : ($count >= $limit * self::WARNING_THRESHOLD ? 'warning' : 'success');
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

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
        ];
    }
}
