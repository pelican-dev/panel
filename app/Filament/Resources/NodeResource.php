<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NodeResource\Pages;
use App\Filament\Resources\NodeResource\RelationManagers;
use App\Models\Node;
use Filament\Resources\Resource;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static ?string $navigationIcon = 'tabler-server-2';

    protected static ?string $recordTitleAttribute = 'name';

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
}
