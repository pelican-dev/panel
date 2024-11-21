<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\AllocationResource\Pages;
use App\Models\Allocation;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;

    protected static ?int $navigationSort = 8;

    protected static ?string $label = 'Network';

    protected static ?string $pluralLabel = 'Network';

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllocations::route('/'),
        ];
    }
}
