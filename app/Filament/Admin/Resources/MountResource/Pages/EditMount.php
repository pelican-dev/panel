<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\MountResource;

class EditMount extends EditRecord
{
    protected static string $resource = MountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            $this->getSaveFormAction()->formId('form'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
