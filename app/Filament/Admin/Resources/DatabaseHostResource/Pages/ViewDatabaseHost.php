<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Filament\Admin\Resources\DatabaseHostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDatabaseHost extends ViewRecord
{
    protected static string $resource = DatabaseHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
