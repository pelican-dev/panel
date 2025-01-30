<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\DatabaseResource\Pages;
use App\Models\Database;
use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationIcon = 'tabler-database';

    public const WARNING_THRESHOLD = 0.7;

    public static function getNavigationBadge(): string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $limit = $server->database_limit;

        return $server->databases->count() . ($limit === 0 ? '' : ' / ' . $limit);
    }

    public static function getNavigationBadgeColor(): ?string
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        $limit = $server->database_limit;
        $count = $server->databases->count();

        if ($limit === 0) {
            return null;
        }

        return $count >= $limit
            ? 'danger'
            : ($count >= $limit * self::WARNING_THRESHOLD ? 'warning' : 'success');
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
        return auth()->user()->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public static function canView(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_DATABASE_DELETE, Filament::getTenant());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabases::route('/'),
        ];
    }
}
