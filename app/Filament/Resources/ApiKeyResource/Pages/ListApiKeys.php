<?php

namespace App\Filament\Resources\ApiKeyResource\Pages;

use App\Filament\Resources\ApiKeyResource;
use App\Models\ApiKey;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListApiKeys extends ListRecords
{
    protected static string $resource = ApiKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Keys'),
            'application' => Tab::make('Application Keys')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('key_type', ApiKey::TYPE_APPLICATION)
                ),
            'account' => Tab::make('Account Keys')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('key_type', ApiKey::TYPE_ACCOUNT)
                ),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'application';
    }
}
