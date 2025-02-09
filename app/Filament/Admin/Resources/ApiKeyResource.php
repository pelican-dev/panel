<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ApiKeyResource\Pages;
use App\Models\ApiKey;
use Filament\Resources\Resource;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon = 'tabler-key';

    public static function getNavigationLabel(): string
    {
        return trans('admin/apikey.nav_title');
    }

    public static function getModelLabel(): string
    {
        return trans('admin/apikey.model_label');
    }

    public static function getPluralModelLabel(): string
    {
        return trans('admin/apikey.model_label_plural');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('key_type', ApiKey::TYPE_APPLICATION)->count() ?: null;
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('admin/dashboard.advanced');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
        ];
    }
}
