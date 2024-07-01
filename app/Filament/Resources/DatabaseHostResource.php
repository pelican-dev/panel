<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatabaseHostResource\Pages;
use App\Models\DatabaseHost;
use Filament\Resources\Resource;

class DatabaseHostResource extends Resource
{
    protected static ?string $model = DatabaseHost::class;

    protected static ?string $label = 'Databases';

    protected static ?string $navigationIcon = 'tabler-database';
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
            'index' => Pages\ListDatabaseHosts::route('/'),
            'create' => Pages\CreateDatabaseHost::route('/create'),
            'edit' => Pages\EditDatabaseHost::route('/{record}/edit'),
        ];
    }
}
