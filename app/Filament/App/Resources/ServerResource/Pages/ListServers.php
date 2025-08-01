<?php

namespace App\Filament\App\Resources\ServerResource\Pages;

use App\Enums\ServerResourceType;
use App\Filament\App\Resources\ServerResource;
use App\Filament\Components\Tables\Columns\ServerEntryColumn;
use App\Filament\Server\Pages\Console;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonPowerRepository;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\ConnectionException;
use Livewire\Attributes\On;

class ListServers extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = ServerResource::class;

    public const DANGER_THRESHOLD = 0.9;

    public const WARNING_THRESHOLD = 0.7;

    private DaemonPowerRepository $daemonPowerRepository;

    public function boot(): void
    {
        $this->daemonPowerRepository = new DaemonPowerRepository();
    }

    /** @return Stack[] */
    protected function gridColumns(): array
    {
        return [
            Stack::make([
                ServerEntryColumn::make('server_entry')
                    ->searchable(['name']),
            ]),
        ];
    }

    /** @return Column[] */
    protected function tableColumns(): array
    {
        return [
            TextColumn::make('condition')
                ->label('Status')
                ->badge()
                ->tooltip(fn (Server $server) => $server->formatResource(ServerResourceType::Uptime))
                ->icon(fn (Server $server) => $server->condition->getIcon())
                ->color(fn (Server $server) => $server->condition->getColor()),
            TextColumn::make('name')
                ->label('Server')
                ->description(fn (Server $server) => $server->description)
                ->grow()
                ->searchable(),
            TextColumn::make('allocation.address')
                ->label('')
                ->badge()
                ->visibleFrom('md')
                ->copyable(request()->isSecure())
                ->state(fn (Server $server) => $server->allocation->address ?? 'None'),
            TextColumn::make('cpuUsage')
                ->label(trans('server/dashboard.resources'))
                ->icon('tabler-cpu')
                ->tooltip(fn (Server $server) => trans('server/dashboard.usage_limit', ['resource' => $server->formatResource(ServerResourceType::CPULimit)]))
                ->state(fn (Server $server) => $server->formatResource(ServerResourceType::CPU))
                ->color(fn (Server $server) => $this->getResourceColor($server, 'cpu')),
            TextColumn::make('memoryUsage')
                ->label('')
                ->icon('tabler-device-desktop-analytics')
                ->tooltip(fn (Server $server) => trans('server/dashboard.usage_limit', ['resource' => $server->formatResource(ServerResourceType::MemoryLimit)]))
                ->state(fn (Server $server) => $server->formatResource(ServerResourceType::Memory))
                ->color(fn (Server $server) => $this->getResourceColor($server, 'memory')),
            TextColumn::make('diskUsage')
                ->label('')
                ->icon('tabler-device-sd-card')
                ->tooltip(fn (Server $server) => trans('server/dashboard.usage_limit', ['resource' => $server->formatResource(ServerResourceType::DiskLimit)]))
                ->state(fn (Server $server) => $server->formatResource(ServerResourceType::Disk))
                ->color(fn (Server $server) => $this->getResourceColor($server, 'disk')),
        ];
    }

    public function table(Table $table): Table
    {
        $baseQuery = auth()->user()->accessibleServers();

        $usingGrid = (auth()->user()->getCustomization()['dashboard_layout'] ?? 'grid') === 'grid';

        return $table
            ->paginated(false)
            ->query(fn () => $baseQuery)
            ->poll('15s')
            ->columns($usingGrid ? $this->gridColumns() : $this->tableColumns())
            ->recordUrl(!$usingGrid ? (fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server)) : null)
            ->actions(!$usingGrid ? ActionGroup::make(static::getPowerActions(view: 'table')) : [])
            ->actionsAlignment(Alignment::Center->value)
            ->contentGrid($usingGrid ? ['default' => 1, 'md' => 2] : null)
            ->emptyStateIcon('tabler-brand-docker')
            ->emptyStateDescription('')
            ->emptyStateHeading(fn () => $this->activeTab === 'my' ? 'You don\'t own any servers!' : 'You don\'t have access to any servers!')
            ->persistFiltersInSession()
            ->filters([
                SelectFilter::make('egg')
                    ->relationship('egg', 'name', fn (Builder $query) => $query->whereIn('id', $baseQuery->pluck('egg_id')))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('owner')
                    ->relationship('user', 'username', fn (Builder $query) => $query->whereIn('id', $baseQuery->pluck('owner_id')))
                    ->searchable()
                    ->hidden(fn () => $this->activeTab === 'my')
                    ->preload(),
            ]);
    }

    public function updatedActiveTab(): void
    {
        $this->resetTable();
    }

    public function getTabs(): array
    {
        $all = auth()->user()->accessibleServers();
        $my = (clone $all)->where('owner_id', auth()->user()->id);
        $other = (clone $all)->whereNot('owner_id', auth()->user()->id);

        return [
            'my' => Tab::make('My Servers')
                ->badge(fn () => $my->count())
                ->modifyQueryUsing(fn () => $my),

            'other' => Tab::make('Others\' Servers')
                ->badge(fn () => $other->count())
                ->modifyQueryUsing(fn () => $other),

            'all' => Tab::make('All Servers')
                ->badge($all->count()),
        ];
    }

    protected function getResourceColor(Server $server, string $resource): ?string
    {
        $current = null;
        $limit = null;

        switch ($resource) {
            case 'cpu':
                $current = $server->retrieveResources()['cpu_absolute'] ?? 0;
                $limit = $server->cpu;
                if ($server->cpu === 0) {
                    return null;
                }
                break;
            case 'memory':
                $current = $server->retrieveResources()['memory_bytes'] ?? 0;
                $limit = $server->memory * 2 ** 20;
                if ($server->memory === 0) {
                    return null;
                }
                break;
            case 'disk':
                $current = $server->retrieveResources()['disk_bytes'] ?? 0;
                $limit = $server->disk * 2 ** 20;
                if ($server->disk === 0) {
                    return null;
                }
                break;
            default:
                return null;
        }

        if ($current >= $limit * self::DANGER_THRESHOLD) {
            return 'danger';
        }

        if ($current >= $limit * self::WARNING_THRESHOLD) {
            return 'warning';
        }

        return null;
    }

    #[On('powerAction')]
    public function powerAction(Server $server, string $action): void
    {
        try {
            $this->daemonPowerRepository->setServer($server)->send($action);

            Notification::make()
                ->title('Power Action')
                ->body($action . ' sent to ' . $server->name)
                ->success()
                ->send();

            cache()->forget("servers.$server->uuid.status");

            $this->redirect(self::getUrl(['activeTab' => $this->activeTab]));
        } catch (ConnectionException) {
            Notification::make()
                ->title(trans('exceptions.node.error_connecting', ['node' => $server->node->name]))
                ->danger()
                ->send();
        }
    }

    /** @return Action[]|ActionGroup[] */
    public static function getPowerActions(string $view): array
    {
        $actions = [
            Action::make('start')
                ->color('primary')
                ->icon('tabler-player-play-filled')
                ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_START, $server))
                ->visible(fn (Server $server) => !$server->isInConflictState() & $server->retrieveStatus()->isStartable())
                ->dispatch('powerAction', fn (Server $server) => ['server' => $server, 'action' => 'start']),
            Action::make('restart')
                ->color('gray')
                ->icon('tabler-reload')
                ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_RESTART, $server))
                ->visible(fn (Server $server) => !$server->isInConflictState() & $server->retrieveStatus()->isRestartable())
                ->dispatch('powerAction', fn (Server $server) => ['server' => $server, 'action' => 'restart']),
            Action::make('stop')
                ->color('danger')
                ->icon('tabler-player-stop-filled')
                ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->visible(fn (Server $server) => !$server->isInConflictState() & $server->retrieveStatus()->isStoppable())
                ->dispatch('powerAction', fn (Server $server) => ['server' => $server, 'action' => 'stop']),
            Action::make('kill')
                ->color('danger')
                ->icon('tabler-alert-square')
                ->tooltip('This can result in data corruption and/or data loss!')
                ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->visible(fn (Server $server) => !$server->isInConflictState() & $server->retrieveStatus()->isKillable())
                ->dispatch('powerAction', fn (Server $server) => ['server' => $server, 'action' => 'kill']),
        ];

        if ($view === 'table') {
            return $actions;
        } else {
            return [
                ActionGroup::make($actions)
                    ->icon('tabler-power')
                    ->color('primary')
                    ->tooltip('Power Actions')
                    ->iconSize(IconSize::Large),
            ];
        }
    }
}
