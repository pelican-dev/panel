<?php

namespace App\Filament\Resources\MountResource\Pages;

use App\Filament\Resources\MountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMounts extends ListRecords
{
    protected static string $resource = MountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
