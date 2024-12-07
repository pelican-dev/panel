<?php

namespace App\Filament\Admin\Resources;

use App\Models\Database;
use Filament\Resources\Resource;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?string $navigationIcon = 'tabler-database';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationGroup = 'Advanced';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\DatabaseResource\Pages\ListDatabases::route('/'),
            'create' => \App\Filament\Admin\Resources\DatabaseResource\Pages\CreateDatabase::route('/create'),
            'edit' => \App\Filament\Admin\Resources\DatabaseResource\Pages\EditDatabase::route('/{record}/edit'),
        ];
    }
}
