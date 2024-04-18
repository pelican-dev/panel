<?php

namespace App\Filament\Resources\DatabaseHostResource\Pages;

use App\Filament\Resources\DatabaseHostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatabaseHost extends EditRecord
{
    protected static string $resource = DatabaseHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
