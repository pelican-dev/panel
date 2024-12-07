<?php

namespace App\Filament\Admin\Resources;

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
            'index' => \App\Filament\Admin\Resources\ServerResource\Pages\ListServers::route('/'),
            'create' => \App\Filament\Admin\Resources\ServerResource\Pages\CreateServer::route('/create'),
            'edit' => \App\Filament\Admin\Resources\ServerResource\Pages\EditServer::route('/{record}/edit'),
        ];
    }
}
