<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServerResource\Pages;
use App\Models\Server;
use Filament\Resources\Resource;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $navigationIcon = 'tabler-brand-docker';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServers::route('/'),
            'create' => Pages\CreateServer::route('/create'),
            'edit' => Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
