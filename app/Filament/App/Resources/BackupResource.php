<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BackupResource\Pages;
use App\Models\Backup;
use Filament\Resources\Resource;

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $navigationIcon = 'tabler-download';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
        ];
    }
}
