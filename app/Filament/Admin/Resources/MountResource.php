<?php

namespace App\Filament\Admin\Resources;

use App\Models\Mount;
use Filament\Resources\Resource;

class MountResource extends Resource
{
    protected static ?string $model = Mount::class;

    protected static ?string $navigationIcon = 'tabler-layers-linked';

    protected static ?string $navigationGroup = 'Advanced';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\MountResource\Pages\ListMounts::route('/'),
            'create' => \App\Filament\Admin\Resources\MountResource\Pages\CreateMount::route('/create'),
            'edit' => \App\Filament\Admin\Resources\MountResource\Pages\EditMount::route('/{record}/edit'),
        ];
    }
}
