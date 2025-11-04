<?php

namespace App\Filament\App\Resources\Servers;

use App\Filament\App\Resources\Servers\Pages\ListServers;
use App\Models\Server;
use Filament\Resources\Resource;

class ServerResource extends Resource
{
    protected static ?string $model = Server::class;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-brand-docker';

    protected static ?string $slug = '/';

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationBadge(): ?string
    {
        return (string) user()?->directAccessibleServers()->where('owner_id', user()?->id)->count();
    }

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

    public static function embedServerList(bool $condition = true): void
    {
        static::$slug = $condition ? null : '/';
        static::$shouldRegisterNavigation = $condition;
    }
}
