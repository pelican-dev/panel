<?php

namespace App\Filament\Admin\Resources\DatabaseHosts\Pages;

use App\Filament\Admin\Resources\DatabaseHosts\DatabaseHostResource;
use App\Models\DatabaseHost;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDatabaseHosts extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = DatabaseHostResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn () => DatabaseHost::count() <= 0),
        ];
    }
}
