<?php

namespace App\Filament\Admin\Resources\MountResource\Pages;

use App\Models\Mount;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\MountResource;

class ListMounts extends ListRecords
{
    protected static string $resource = MountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn () => Mount::count() <= 0),
        ];
    }
}
