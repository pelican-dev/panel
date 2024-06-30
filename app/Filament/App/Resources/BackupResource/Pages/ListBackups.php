<?php

namespace App\Filament\App\Resources\BackupResource\Pages;

use App\Filament\App\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
