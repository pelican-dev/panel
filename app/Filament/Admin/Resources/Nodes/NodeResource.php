<?php

namespace App\Filament\Admin\Resources\Nodes;

use App\Enums\CustomizationKey;
use App\Filament\Admin\Resources\Nodes\Pages\CreateNode;
use App\Filament\Admin\Resources\Nodes\Pages\EditNode;
use App\Filament\Admin\Resources\Nodes\Pages\ListNodes;
use App\Filament\Admin\Resources\Nodes\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\Nodes\RelationManagers\ServersRelationManager;
use App\Models\Node;
use App\Traits\Filament\CanCustomizePages;
use App\Traits\Filament\CanCustomizeRelations;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class NodeResource extends Resource
{
    use CanCustomizePages;
    use CanCustomizeRelations;

    protected static ?string $model = Node::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-server-2';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return trans('admin/node.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/node.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/node.model_label_plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return user()?->getCustomization(CustomizationKey::TopNavigation) ? false : trans('admin/dashboard.server');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            AllocationsRelationManager::class,
            ServersRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => ListNodes::route('/'),
            'create' => CreateNode::route('/create'),
            'edit' => EditNode::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->whereIn('id', user()?->accessibleNodes()->pluck('id'));
    }
}
