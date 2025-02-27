<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Admin\Resources\MountResource;

class ViewMount extends ViewRecord
{
    protected static string $resource = MountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
