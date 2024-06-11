<?php

namespace App\Filament\Pages;

use App\Filament\Resources\NodeResource\Pages\ListNodes;
use App\Models\Egg;
use App\Models\Node;
use App\Models\Server;
use App\Models\User;
use App\Services\Helpers\SoftwareVersionService;
use Filament\Actions\CreateAction;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'tabler-layout-dashboard';

    protected static string $view = 'filament.pages.dashboard';

    protected ?string $heading = '';

    public function getTitle(): string
    {
        return trans('strings.dashboard');
    }

    protected static ?string $slug = '/';

    public string $activeTab = 'nodes';

    public function getViewData(): array
    {
        /** @var SoftwareVersionService $softwareVersionService */
        $softwareVersionService = app(SoftwareVersionService::class);

        return [
            'inDevelopment' => config('app.version') === 'canary',
            'version' => $softwareVersionService->versionData()['version'],
            'latestVersion' => $softwareVersionService->getPanel(),
            'isLatest' => $softwareVersionService->isLatestPanel(),
            'eggsCount' => Egg::query()->count(),
            'nodesList' => ListNodes::getUrl(),
            'nodesCount' => Node::query()->count(),
            'serversCount' => Server::query()->count(),
            'usersCount' => User::query()->count(),

            'devActions' => [
                CreateAction::make()
                    ->label('Bugs & Features')
                    ->icon('tabler-brand-github')
                    ->url('https://github.com/pelican-dev/panel/discussions', true),
            ],
            'updateActions' => [
                CreateAction::make()
                    ->label('Read Documentation')
                    ->icon('tabler-clipboard-text')
                    ->url('https://pelican.dev/docs/panel/update', true)
                    ->color('warning'),
            ],
            'nodeActions' => [
                CreateAction::make()
                    ->label(trans('dashboard/index.sections.intro-first-node.button_label'))
                    ->icon('tabler-server-2')
                    ->url(route('filament.admin.resources.nodes.create')),
            ],
            'supportActions' => [
                CreateAction::make()
                    ->label(trans('dashboard/index.sections.intro-support.button_donate'))
                    ->icon('tabler-cash')
                    ->url($softwareVersionService->getDonations(), true)
                    ->color('success'),
            ],
            'helpActions' => [
                CreateAction::make()
                    ->label(trans('dashboard/index.sections.intro-help.button_docs'))
                    ->icon('tabler-speedboat')
                    ->url('https://pelican.dev/docs', true),
            ],
        ];
    }
}
