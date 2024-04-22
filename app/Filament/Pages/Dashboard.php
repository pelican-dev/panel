<?php

namespace App\Filament\Pages;

use App\Filament\Resources\NodeResource\Pages\ListNodes;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\User;
use Filament\Actions\CreateAction;
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

            'devActions' => [
                CreateAction::make()
                    ->label('Create Issue')
                    ->icon('tabler-brand-github')
                    ->url('https://github.com/pelican-dev/panel/issues/new/choose', true)
                    ->color('warning'),
                CreateAction::make()
                    ->label('Discuss Features')
                    ->icon('tabler-brand-github')
                    ->url('https://github.com/pelican-dev/panel/discussions', true)
                    ->color('primary'),
            ],
            'nodeActions' => [
                CreateAction::make()
                    ->label('Create first Node in Pelican')
                    ->icon('tabler-server-2')
                    ->url(route('filament.admin.resources.nodes.create'))
                    ->color('primary'),
            ],
            'supportActions' => [
                CreateAction::make()
                    ->label('Help Translate')
                    ->icon('tabler-language')
                    ->url('https://crowdin.com/project/pelican-dev', true)
                    ->color('info'),
                CreateAction::make()
                    ->label('Donate Directly')
                    ->icon('tabler-cash')
                    ->url('https://pelican.dev/donate', true)
                    ->color('success'),
            ],
            'helpActions' => [
                CreateAction::make()
                    ->label('Read Documentation')
                    ->icon('tabler-speedboat')
                    ->url('https://pelican.dev/docs', true)
                    ->color('info'),
                CreateAction::make()
                    ->label('Get Help in Discord')
                    ->icon('tabler-brand-discord')
                    ->url('https://discord.gg/pelican-panel', true)
                    ->color('primary'),
            ],
        ];
    }
}
