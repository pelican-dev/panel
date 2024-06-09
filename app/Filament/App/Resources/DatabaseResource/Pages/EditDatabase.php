<?php

namespace App\Filament\App\Resources\DatabaseResource\Pages;

use App\Filament\App\Resources\DatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDatabase extends EditRecord
{
    protected static string $resource = DatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
