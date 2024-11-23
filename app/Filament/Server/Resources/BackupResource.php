<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\BackupResource\Pages;
use App\Models\Backup;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static ?int $navigationSort = 4;

    protected static bool $canCreateAnother = false;

    protected static ?string $navigationIcon = 'tabler-file-zip';

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
            'index' => Pages\ListBackups::route('/'),
        ];
    }
}
