<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NodeResource\Pages;
use App\Filament\Admin\Resources\NodeResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'tabler-server-2';

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
        return !empty(auth()->user()->getCustomization()['top_navigation']) ? false : trans('admin/dashboard.server');

    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    /** @return class-string<RelationManager>[] */
    public static function getDefaultRelations(): array
    {
        return [
            RelationManagers\AllocationsRelationManager::class,
            RelationManagers\NodesRelationManager::class,
        ];
    }

    /** @return array<string, PageRegistration> */
    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListNodes::route('/'),
            'create' => Pages\CreateNode::route('/create'),
            'edit' => Pages\EditNode::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        return $query->whereIn('id', auth()->user()->accessibleNodes()->pluck('id'));
    }
}
