<?php

namespace App\Filament\Client\Pages;

use Filament\Pages\Page;
use App\Models\Server;

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
        $servers = Server::where('uuid', $user->id)->get();

        return [
            'heading' => 'Welcome to ' . config('app.name'),
            'subheading' => 'Welcome back ' . $user->name_first . ' ' . $user->name_last,
            'ServersCount' => $servers->count(),
        ];
    }
}
