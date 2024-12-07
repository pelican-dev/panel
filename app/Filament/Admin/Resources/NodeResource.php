<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NodeResource\RelationManagers\AllocationsRelationManager;
use App\Filament\Admin\Resources\NodeResource\RelationManagers\NodesRelationManager;
use App\Models\Node;
use Filament\Resources\Resource;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static ?string $navigationIcon = 'tabler-server-2';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getRelations(): array
    {
        return [
            AllocationsRelationManager::class,
            NodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\NodeResource\Pages\ListNodes::route('/'),
            'create' => \App\Filament\Admin\Resources\NodeResource\Pages\CreateNode::route('/create'),
            'edit' => \App\Filament\Admin\Resources\NodeResource\Pages\EditNode::route('/{record}/edit'),
        ];
    }
}
