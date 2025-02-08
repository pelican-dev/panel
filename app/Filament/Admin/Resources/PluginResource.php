<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PluginResource\Pages\ListPlugins;
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
            'index' => ListPlugins::route('/'),
        ];
    }
}
