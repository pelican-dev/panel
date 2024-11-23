<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\FileResource\Pages;
use App\Models\File;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'tabler-files';

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
            'edit' => Pages\EditFiles::route('/edit/{path}'),
            'search' => Pages\SearchFiles::route('/search/{searchTerm}'), // TODO: find better way?
            'index' => Pages\ListFiles::route('/{path?}'),
        ];
    }
}
