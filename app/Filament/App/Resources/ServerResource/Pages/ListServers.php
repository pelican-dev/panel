<?php

namespace App\Filament\App\Resources\ServerResource\Pages;

use App\Enums\ServerResourceType;
use App\Filament\App\Resources\ServerResource;
use App\Filament\Components\Tables\Columns\ServerEntryColumn;
use App\Filament\Server\Pages\Console;
use App\Models\Permission;
use App\Models\Server;
use App\Repositories\Daemon\DaemonPowerRepository;
use AymanAlhattami\FilamentContextMenu\Columns\ContextMenuTextColumn;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\ConnectionException;
use Livewire\Attributes\On;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    public const DANGER_THRESHOLD = 0.9;

    public const WARNING_THRESHOLD = 0.7;

    private DaemonPowerRepository $daemonPowerRepository;

    public function boot(): void
    {
        $this->daemonPowerRepository = new DaemonPowerRepository();
    }

    public function table(Table $table): Table
    {
        $baseQuery = auth()->user()->accessibleServers();

        $menuOptions = function (Server $server) {
            $status = $server->retrieveStatus();
            $user = auth()->user();

            $actions = [
                'start' => [
                    'permission' => Permission::ACTION_CONTROL_START,
                    'check' => $status->isStartable(),
                    'color' => 'primary',
                    'icon' => 'tabler-player-play-filled',
                ],
                'restart' => [
                    'permission' => Permission::ACTION_CONTROL_RESTART,
                    'check' => $status->isRestartable(),
                    'color' => 'gray',
                    'icon' => 'tabler-refresh',
                ],
                'stop' => [
                    'permission' => Permission::ACTION_CONTROL_STOP,
                    'check' => $status->isStoppable(),
                    'color' => 'danger',
                    'icon' => 'tabler-player-stop-filled',
                ],
                'kill' => [
                    'permission' => Permission::ACTION_CONTROL_STOP,
                    'check' => $status->isKillable(),
                    'color' => 'danger',
                    'icon' => 'tabler-alert-square',
                    'tooltip' => 'This can result in data corruption and/or data loss!',
                ],
            ];

            $options = [];

            foreach ($actions as $name => $details) {
                if ($user->can($details['permission'], $server) && $details['check']) {
                    $action = Action::make($name)
                        ->color($details['color'])
                        ->dispatch('powerAction', ['server' => $server, 'action' => $name])
                        ->icon($details['icon']);

                    if (isset($details['tooltip'])) {
                        $action->tooltip($details['tooltip']);
                    }

                    $options[] = $action;
                }
            }

            return $options;
        };

        $viewOne = [
            ContextMenuTextColumn::make('condition')
                ->label('')
                ->default('unknown')
                ->wrap()
                ->badge()
                ->alignCenter()
                ->tooltip(fn (Server $server) => $server->formatResource('uptime', type: ServerResourceType::Time))
                ->icon(fn (Server $server) => $server->condition->getIcon())
                ->color(fn (Server $server) => $server->condition->getColor())
                ->contextMenuActions($menuOptions)
                ->enableContextMenu(fn (Server $server) => !$server->isInConflictState()),
        ];

        $viewTwo = [
            ContextMenuTextColumn::make('name')
                ->label('')
                ->size('md')
                ->searchable()
                ->contextMenuActions($menuOptions)
                ->enableContextMenu(fn (Server $server) => !$server->isInConflictState()),
            ContextMenuTextColumn::make('allocation.address')
                ->label('')
                ->badge()
                ->copyable(request()->isSecure())
                ->contextMenuActions($menuOptions)
                ->enableContextMenu(fn (Server $server) => !$server->isInConflictState()),
        ];

        $viewThree = [
            TextColumn::make('cpuUsage')
                ->label('')
                ->icon('tabler-cpu')
                ->tooltip(fn (Server $server) => 'Usage Limit: ' . $server->formatResource('cpu', limit: true, type: ServerResourceType::Percentage, precision: 0))
                ->state(fn (Server $server) => $server->formatResource('cpu_absolute', type: ServerResourceType::Percentage))
                ->color(fn (Server $server) => $this->getResourceColor($server, 'cpu')),
            TextColumn::make('memoryUsage')
                ->label('')
                ->icon('tabler-memory')
                ->tooltip(fn (Server $server) => 'Usage Limit: ' . $server->formatResource('memory', limit: true))
                ->state(fn (Server $server) => $server->formatResource('memory_bytes'))
                ->color(fn (Server $server) => $this->getResourceColor($server, 'memory')),
            TextColumn::make('diskUsage')
                ->label('')
                ->icon('tabler-device-floppy')
                ->tooltip(fn (Server $server) => 'Usage Limit: ' . $server->formatResource('disk', limit: true))
                ->state(fn (Server $server) => $server->formatResource('disk_bytes'))
                ->color(fn (Server $server) => $this->getResourceColor($server, 'disk')),
        ];

        return $table
            ->paginated(false)
            ->query(fn () => $baseQuery)
            ->poll('15s')
            ->columns(
                (auth()->user()->getCustomization()['dashboard_layout'] ?? 'grid') === 'grid'
                    ? [
                        Stack::make([
                            ServerEntryColumn::make('server_entry')
                                ->searchable(['name']),
                        ]),
                    ]
                    : [
                        ColumnGroup::make('Status')
                            ->label('Status')
                            ->columns($viewOne),
                        ColumnGroup::make('Server')
                            ->label('Servers')
                            ->columns($viewTwo),
                        ColumnGroup::make('Resources')
                            ->label('Resources')
                            ->columns($viewThree),
                    ]
            )
            ->recordUrl(fn (Server $server) => Console::getUrl(panel: 'server', tenant: $server))
            ->contentGrid([
                'default' => 1,
                'md' => 2,
            ])
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

    public function getResourceColor(Server $server, string $resource): ?string
    {
        $current = null;
        $limit = null;

        switch ($resource) {
            case 'cpu':
                $current = $server->resources()['cpu_absolute'] ?? 0;
                $limit = $server->cpu;
                if ($server->cpu === 0) {
                    return null;
                }
                break;

            case 'memory':
                $current = $server->resources()['memory_bytes'] ?? 0;
                $limit = $server->memory * 2 ** 20;
                if ($server->memory === 0) {
                    return null;
                }
                break;

            case 'disk':
                $current = $server->resources()['disk_bytes'] ?? 0;
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

            $this->redirect(self::getUrl(['activeTab' => $this->activeTab]));
        } catch (ConnectionException) {
            Notification::make()
                ->title(trans('exceptions.node.error_connecting', ['node' => $server->node->name]))
                ->danger()
                ->send();
        }
    }
}
