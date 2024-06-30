<?php

namespace App\Filament\App\Resources\BackupResource\Pages;

use App\Filament\App\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBackup extends EditRecord
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
