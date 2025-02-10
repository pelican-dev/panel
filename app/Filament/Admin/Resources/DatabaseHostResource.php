<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DatabaseHostResource\Pages;
use App\Models\DatabaseHost;
use Filament\Resources\Resource;

class DatabaseHostResource extends Resource
{
    protected static ?string $model = DatabaseHost::class;

    protected static ?string $navigationIcon = 'tabler-database';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationLabel(): string
    {
        return trans('admin/databasehost.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/databasehost.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/databasehost.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
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
