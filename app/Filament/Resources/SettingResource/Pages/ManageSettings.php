<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageSettings extends ManageRecords
{
    protected static string $resource = SettingResource::class;

    public function getTabs(): array
    {
        return [
            'panel' => Tab::make('Panel Settings')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tabs', 'Panel')),
            'mail' => Tab::make('Mail Settings')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tabs', 'Mail')),
            'Advanced' => Tab::make('Advanced Settings')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tabs', 'Advanced')),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'panel';
    }
}
