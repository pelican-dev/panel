<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'tabler-settings';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 12;

    public function getTitle(): string
    {
        return __('strings.settings');
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Keys'),
            'application' => Tab::make('Application Keys')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('key_type', 'a')
                ),
            'account' => Tab::make('Account Keys')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('key_type', 'b')
                ),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'all';
    }
}
