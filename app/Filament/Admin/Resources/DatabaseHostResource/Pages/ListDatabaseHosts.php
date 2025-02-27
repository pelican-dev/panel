<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Models\DatabaseHost;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\DatabaseHostResource;

class ListDatabaseHosts extends ListRecords
{
    protected static string $resource = DatabaseHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn () => DatabaseHost::count() <= 0),
        ];
    }
}
