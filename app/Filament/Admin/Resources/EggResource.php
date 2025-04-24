<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EggResource\Pages;
use App\Models\Egg;
use Filament\Resources\Resource;

class EggResource extends Resource
{
    protected static ?string $model = Egg::class;

    protected static ?string $navigationIcon = 'tabler-eggs';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return config('panel.filament.top-navigation', false) ? null : trans('admin/dashboard.server');
    }

    public static function getNavigationLabel(): string
    {
        return trans('admin/egg.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/egg.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/egg.model_label_plural');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'tags', 'uuid', 'id'];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEggs::route('/'),
            'create' => Pages\CreateEgg::route('/create'),
            'edit' => Pages\EditEgg::route('/{record}/edit'),
        ];
    }
}
