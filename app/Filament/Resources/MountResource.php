<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MountResource\Pages;
use App\Models\Mount;
use Filament\Resources\Resource;

class MountResource extends Resource
{
    protected static ?string $model = Mount::class;

    protected static ?string $navigationIcon = 'tabler-layers-linked';
    protected static ?string $navigationGroup = 'Advanced';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMounts::route('/'),
            'create' => Pages\CreateMount::route('/create'),
            'edit' => Pages\EditMount::route('/{record}/edit'),
        ];
    }
}
