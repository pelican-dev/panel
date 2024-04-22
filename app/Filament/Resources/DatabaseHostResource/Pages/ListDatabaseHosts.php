<?php

namespace App\Filament\Resources\DatabaseHostResource\Pages;

use App\Filament\Resources\DatabaseHostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseHosts extends ListRecords
{
    protected static string $resource = DatabaseHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
