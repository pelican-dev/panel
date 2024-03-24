<?php

namespace App\Filament\Resources\EggResource\Pages;

use App\Filament\Resources\EggResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEggs extends ListRecords
{
    protected static string $resource = EggResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
