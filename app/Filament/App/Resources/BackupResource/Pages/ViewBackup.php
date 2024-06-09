<?php

namespace App\Filament\App\Resources\BackupResource\Pages;

use App\Filament\App\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBackup extends ViewRecord
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
