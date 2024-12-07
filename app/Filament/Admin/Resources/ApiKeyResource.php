<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ApiKeyResource\Pages;
use App\Models\ApiKey;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $label = 'API Key';

    protected static ?string $navigationIcon = 'tabler-key';

    protected static ?string $navigationGroup = 'Advanced';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('key_type', ApiKey::TYPE_APPLICATION)->count() ?: null;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
        ];
    }
}
