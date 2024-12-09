<?php

namespace App\Filament\Server\Pages;

use App\Enums\ContainerStatus;
use App\Filament\Server\Widgets\ServerConsole;
use App\Filament\Server\Widgets\ServerCpuChart;
use App\Filament\Server\Widgets\ServerMemoryChart;
// use App\Filament\Server\Widgets\ServerNetworkChart;
use App\Filament\Server\Widgets\ServerOverview;
use App\Models\Server;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Livewire\Attributes\On;

class Console extends Page
{
    protected static ?string $navigationIcon = 'tabler-brand-tabler';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.server.pages.console';

    public ContainerStatus $status = ContainerStatus::Missing;

    public function getWidgetData(): array
    {
        return [
            'server' => Filament::getTenant(),
            'user' => auth()->user(),
        ];
    }

    public function getWidgets(): array
    {
        return [
            ServerOverview::class,
            ServerConsole::class,
            ServerCpuChart::class,
            ServerMemoryChart::class,
            //ServerNetworkChart::class, TODO: convert units.
        ];
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int|string|array
    {
        return 3;
    }

    #[On('powerChanged')]
    public function powerChanged(string $state): void
    {
        $this->status = ContainerStatus::from($state);

        $this->cachedHeaderActions = [];

        $this->cacheHeaderActions();
    }

    protected function getHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            Action::make('start')
                ->color('primary')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'start'))
                ->disabled(fn () => $server->isInConflictState() || in_array($this->status, [ContainerStatus::Running, ContainerStatus::Starting, ContainerStatus::Stopping, ContainerStatus::Restarting])),
            Action::make('restart')
                ->color('gray')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'restart'))
                ->disabled(fn () => $server->isInConflictState() || $this->status !== ContainerStatus::Running),
            Action::make('stop')
                ->color('danger')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'stop'))
                ->hidden(fn () => in_array($this->status, [ContainerStatus::Stopping, ContainerStatus::Restarting, ContainerStatus::Starting]))
                ->disabled(fn () => $server->isInConflictState() || in_array($this->status, [ContainerStatus::Starting, ContainerStatus::Stopping, ContainerStatus::Restarting, ContainerStatus::Exited, ContainerStatus::Offline])),
            Action::make('kill')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Do you wish to kill this server?')
                ->modalDescription('This can result in data corruption and/or data loss!')
                ->modalSubmitActionLabel('Kill Server')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'kill'))
                ->hidden(fn () => $server->isInConflictState() || in_array($this->status, [ContainerStatus::Running, ContainerStatus::Restarting, ContainerStatus::Offline, ContainerStatus::Removing, ContainerStatus::Dead, ContainerStatus::Exited, ContainerStatus::Created]))
                ->disabled(fn () => $server->isInConflictState() || $this->status === ContainerStatus::Offline),
        ];
    }
}
