<?php

namespace App\Filament\Admin\Resources\DatabaseHostResource\Pages;

use App\Filament\Admin\Resources\DatabaseHostResource;
use App\Filament\Admin\Resources\DatabaseHostResource\RelationManagers\DatabasesRelationManager;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDatabaseHost extends ViewRecord
{
    protected static string $resource = DatabaseHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        if (DatabasesRelationManager::canViewForRecord($this->getRecord(), static::class)) {
            return [
                DatabasesRelationManager::class,
            ];
        }

        return [];
    }
}
