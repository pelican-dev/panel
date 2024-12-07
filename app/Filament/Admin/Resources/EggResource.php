<?php

namespace App\Filament\Admin\Resources;

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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'tags', 'uuid', 'id'];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\EggResource\Pages\ListEggs::route('/'),
            'create' => \App\Filament\Admin\Resources\EggResource\Pages\CreateEgg::route('/create'),
            'edit' => \App\Filament\Admin\Resources\EggResource\Pages\EditEgg::route('/{record}/edit'),
        ];
    }
}
