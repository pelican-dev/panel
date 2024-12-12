<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ApiKeyResource\Pages;
use App\Models\ApiKey;
use Filament\Resources\Resource;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $modelLabel = 'Application API Key';

    protected static ?string $pluralModelLabel = 'Application API Keys';

    protected static ?string $navigationLabel = 'API Keys';

    protected static ?string $navigationIcon = 'tabler-key';

    protected static ?string $navigationGroup = 'Advanced';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('key_type', ApiKey::TYPE_APPLICATION)->count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
        ];
    }
}
