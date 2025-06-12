<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use App\Filament\Admin\Resources\MountResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMount extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = MountResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
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
