<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\AllocationResource\Pages;
use App\Models\Allocation;
use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;

    protected static ?string $label = 'Network';

    protected static ?string $pluralLabel = 'Network';

    protected static ?int $navigationSort = 7;

    protected static ?string $navigationIcon = 'tabler-network';

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
        return auth()->user()->can(Permission::ACTION_ALLOCATION_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_ALLOCATION_DELETE, Filament::getTenant());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllocations::route('/'),
        ];
    }
}
