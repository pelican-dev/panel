<?php

namespace App\Filament\App\Resources\Servers;

use App\Filament\App\Resources\Servers\Pages\ListServers;
use App\Models\Server;
use Filament\Resources\Resource;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static ?string $slug = '/';

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServers::route('/'),
        ];
    }
}
