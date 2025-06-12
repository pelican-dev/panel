<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = UserResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
