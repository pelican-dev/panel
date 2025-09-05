<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EggResource\Pages;
use App\Filament\Admin\Resources\EggResource\RelationManagers;
use App\Models\Egg;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;

class EggResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;

    protected static ?string $model = Egg::class;

    protected static ?string $navigationIcon = 'tabler-eggs';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return !empty(auth()->user()->getCustomization()['top_navigation']) ? false : trans('admin/dashboard.server');
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

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            RelationManagers\ServersRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListEggs::route('/'),
            'create' => Pages\CreateEgg::route('/create'),
            'edit' => Pages\EditEgg::route('/{record}/edit'),
        ];
    }
}
