<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DatabaseResource\Pages;
use App\Models\Database;
use Filament\Resources\Resource;

class DatabaseResource extends Resource
{
    protected static ?string $model = Database::class;

    protected static ?string $navigationIcon = 'tabler-database';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDatabases::route('/'),
        ];
    }
}
