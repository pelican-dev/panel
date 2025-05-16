<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NodeResource\Pages;
use App\Filament\Admin\Resources\NodeResource\RelationManagers;
use App\Models\Node;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class NodeResource extends Resource
{
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
        return config('panel.filament.top-navigation', false) ? null : trans('admin/dashboard.server');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count() ?: null;
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AllocationsRelationManager::class,
            RelationManagers\NodesRelationManager::class,
        ];
    }

    public static function getPages(): array
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
