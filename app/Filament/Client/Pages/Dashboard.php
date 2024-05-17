<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'tabler-layout-dashboard';

    protected static string $view = 'filament.client.pages.dashboard';

    protected ?string $heading = '';

    public function getTitle(): string
    {
        return trans('strings.dashboard');
    }

    protected static ?string $slug = '/';

    public string $activeTab = 'nodes';

    public function getViewData(): array
    {
        $user = auth()->user();

        return [
            'heading' => 'Welcome to ' . config('app.name'),
            'subheading' => 'Welcome back ' . $user->name_first . " " . $user->name_last,
        ];
    }

}
