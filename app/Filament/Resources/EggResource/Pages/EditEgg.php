<?php

namespace App\Filament\Resources\EggResource\Pages;

use App\Filament\Resources\EggResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEgg extends EditRecord
{
    protected static string $resource = EggResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
