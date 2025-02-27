<?php

namespace App\Filament\App\Resources;

use App\Models\Server;
use Filament\Resources\Resource;
use App\Filament\App\Resources\ServerResource\Pages;

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
            'index' => Pages\ListServers::route('/'),
        ];
    }
}
