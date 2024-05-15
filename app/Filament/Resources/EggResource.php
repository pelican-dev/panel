<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EggResource\Pages;
use App\Models\Egg;
use Filament\Resources\Resource;

class EggResource extends Resource
{
    protected static ?string $model = Egg::class;

    protected static ?string $navigationIcon = 'tabler-eggs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordRouteKeyName = 'id';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
