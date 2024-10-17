<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\AllocationResource\Pages;
use App\Models\Allocation;
use Filament\Resources\Resource;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;
    protected static ?int $navigationSort = 8;
    protected static ?string $label = 'Network';
    protected static ?string $pluralLabel = 'Network';
    protected static ?string $navigationIcon = 'tabler-network';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllocations::route('/'),
        ];
    }
}
