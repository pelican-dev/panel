<?php

namespace App\Filament\Pages;

use App\Filament\Resources\NodeResource\Pages\ListNodes;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\User;
use Filament\Pages\Page;

class Introduction extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.introduction';

    protected ?string $heading = '';

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
