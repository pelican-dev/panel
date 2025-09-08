<?php

namespace App\Filament\Admin\Resources\ApiKeys\Pages;

use App\Filament\Admin\Resources\ApiKeys\ApiKeyResource;
use App\Models\ApiKey;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiKeys extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ApiKeyResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn () => ApiKey::where('key_type', ApiKey::TYPE_APPLICATION)->count() <= 0),
        ];
    }
}
