<?php

namespace App\Filament\Admin\Resources\Eggs;

use App\Enums\CustomizationKey;
use App\Filament\Admin\Resources\Eggs\Pages\CreateEgg;
use App\Filament\Admin\Resources\Eggs\Pages\EditEgg;
use App\Filament\Admin\Resources\Eggs\Pages\ListEggs;
use App\Filament\Admin\Resources\Eggs\RelationManagers\ServersRelationManager;
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

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-eggs';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return ($count = static::getModel()::count()) > 0 ? (string) $count : null;
    }

    public static function getNavigationGroup(): ?string
    {
        return user()?->getCustomization(CustomizationKey::TopNavigation) ? false : trans('admin/dashboard.server');
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
            ServersRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListEggs::route('/'),
            'create' => CreateEgg::route('/create'),
            'edit' => EditEgg::route('/{record}/edit'),
        ];
    }
}
