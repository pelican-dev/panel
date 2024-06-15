<?php

namespace App\Filament;

use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'tabler-settings';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 12;

    public function getTitle(): string
    {
        return trans('strings.settings');
    }
}
