<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApiKeyResource\Pages;
use App\Models\ApiKey;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class ApiKeyResource extends Resource
{
    protected static ?string $model = ApiKey::class;

    protected static ?string $navigationIcon = 'tabler-key';

    public static function canEdit($record): bool
    {
        return false;
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Keys'),
            'application' => Tab::make('Application Keys')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('key_type', ApiKey::TYPE_APPLICATION)),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'application';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiKeys::route('/'),
            'create' => Pages\CreateApiKey::route('/create'),
        ];
    }
}
