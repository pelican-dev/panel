<?php

namespace App\Filament\Admin\Resources\ApiKeyResource\Pages;

use App\Filament\Admin\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiKeys extends ListRecords
{
    protected static string $resource = ApiKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(trans('admin/apikey.create_action', ['action' => trans('filament-actions::create.single.modal.actions.create.label')]))
                ->hidden(fn () => ApiKey::where('key_type', ApiKey::TYPE_APPLICATION)->count() <= 0),
        ];
    }
}
