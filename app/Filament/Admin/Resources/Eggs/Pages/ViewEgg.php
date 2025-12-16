<?php

namespace App\Filament\Admin\Resources\Eggs\Pages;

use App\Filament\Admin\Resources\Eggs\EggResource;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewEgg extends ViewRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    public function form(Schema $schema): Schema
    {
        return EggResource::schema($schema);
    }
}
