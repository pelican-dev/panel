<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\DatabaseResource\Pages;
use App\Models\Database;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'tabler-database';

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabases::route('/'),
        ];
    }
}
