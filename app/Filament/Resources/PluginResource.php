<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginResource\Pages;
use App\Models\Plugin;
use Filament\Resources\Resource;

class PluginResource extends Resource
{
    protected static ?string $model = Plugin::class;

    protected static ?string $navigationIcon = 'tabler-packages';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlugins::route('/'),
        ];
    }
}
