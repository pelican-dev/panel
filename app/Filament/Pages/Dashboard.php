<?php

namespace App\Filament\Pages;

use App\Filament\Resources\NodeResource\Pages\ListNodes;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\User;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'tabler-layout-dashboard';

    protected static string $view = 'filament.pages.dashboard';

    protected ?string $heading = '';

    protected static ?string $title = 'Dashboard';

    protected static ?string $slug = '/';

    public string $activeTab = 'nodes';

    public function getViewData(): array
    {
        return [
            'inDevelopment' => config('app.version') === 'canary',
            'eggsCount' => Egg::query()->count(),
            'nodesList' => ListNodes::getUrl(),
            'nodesCount' => Node::query()->count(),
            'serversCount' => Server::query()->count(),
            'usersCount' => User::query()->count(),
        ];
    }
}
