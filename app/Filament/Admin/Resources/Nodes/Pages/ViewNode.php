<?php

namespace App\Filament\Admin\Resources\Nodes\Pages;

use App\Filament\Admin\Resources\Nodes\NodeResource;
use App\Repositories\Daemon\DaemonSystemRepository;
use App\Services\Nodes\NodeUpdateService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use App\Traits\Filament\CanCustomizeTabs;
use App\Traits\Filament\NodeDetailTabs;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ViewNode extends ViewRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;
    use CanCustomizeTabs;
    use NodeDetailTabs;

    protected static string $resource = NodeResource::class;

    private DaemonSystemRepository $daemonSystemRepository;

    private NodeUpdateService $nodeUpdateService;

    public function boot(DaemonSystemRepository $daemonSystemRepository, NodeUpdateService $nodeUpdateService): void
    {
        $this->daemonSystemRepository = $daemonSystemRepository;
        $this->nodeUpdateService = $nodeUpdateService;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Tabs')
                ->columns([
                    'default' => 2,
                    'sm' => 3,
                    'md' => 3,
                    'lg' => 4,
                ])
                ->persistTabInQueryString()
                ->columnSpanFull()
                ->tabs($this->getTabs()),
        ]);
    }

    /** @return Tab[] */
    protected function getDefaultTabs(): array
    {
        return $this->detailTabs();
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getColumnSpan(): ?int
    {
        return null;
    }

    protected function getColumnStart(): ?int
    {
        return null;
    }
}
