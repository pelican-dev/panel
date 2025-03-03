<?php

namespace App\Filament\Server\Pages;

use App\Enums\ContainerStatus;
use App\Exceptions\Http\Server\ServerStateConflictException;
use App\Filament\Server\Widgets\ServerConsole;
use App\Filament\Server\Widgets\ServerCpuChart;
use App\Filament\Server\Widgets\ServerMemoryChart;
// use App\Filament\Server\Widgets\ServerNetworkChart;
use App\Filament\Server\Widgets\ServerOverview;
use App\Livewire\AlertBanner;
use App\Models\Permission;
use App\Models\Server;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Livewire\Attributes\On;

class Console extends Page
{
    protected static ?string $navigationIcon = 'tabler-brand-tabler';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.server.pages.console';

    public ContainerStatus $status = ContainerStatus::Offline;

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        try {
            $server->validateCurrentState();
        } catch (ServerStateConflictException $exception) {
            AlertBanner::make()
                ->warning()
                ->title('Warning')
                ->body($exception->getMessage())
                ->send();
        }
    }

    public function getWidgetData(): array
    {
        return [
            'server' => Filament::getTenant(),
            'user' => auth()->user(),
        ];
    }

    /**
     * @return class-string<Widget>[]
     */
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

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    public function getColumns(): int
    {
        return 3;
    }

    #[On('console-status')]
    public function receivedConsoleUpdate(?string $state = null): void
    {
        if ($state) {
            $this->status = ContainerStatus::from($state);
        }

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
                ->action(fn () => $this->dispatch('setServerState', state: 'start', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_START, $server))
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isStartable()),
            Action::make('restart')
                ->color('gray')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'restart', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_RESTART, $server))
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isRestartable()),
            Action::make('stop')
                ->color('danger')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'stop', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->hidden(fn () => $this->status->isStartingOrStopping() || $this->status->isKillable())
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isStoppable()),
            Action::make('kill')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Do you wish to kill this server?')
                ->modalDescription('This can result in data corruption and/or data loss!')
                ->modalSubmitActionLabel('Kill Server')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'kill', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->hidden(fn () => $server->isInConflictState() || !$this->status->isKillable()),
        ];
    }
}
