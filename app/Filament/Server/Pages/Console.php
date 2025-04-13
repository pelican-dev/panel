<?php

namespace App\Filament\Server\Pages;

use App\Enums\ConsoleWidgetPosition;
use App\Enums\ContainerStatus;
use App\Exceptions\Http\Server\ServerStateConflictException;
use App\Features;
use App\Features\Feature;
use App\Filament\Server\Widgets\ServerConsole;
use App\Filament\Server\Widgets\ServerCpuChart;
use App\Filament\Server\Widgets\ServerMemoryChart;
// use App\Filament\Server\Widgets\ServerNetworkChart;
use App\Filament\Server\Widgets\ServerOverview;
use App\Livewire\AlertBanner;
use App\Models\Permission;
use App\Models\Server;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

class Console extends Page implements HasForms
{
    use InteractsWithActions;

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
            AlertBanner::make('server_conflict')
                ->title('Warning')
                ->body($exception->getMessage())
                ->warning()
                ->send();
        }
    }

    public function boot(): void
    {
        foreach ($this->getActiveFeatures() as $feature) {
            $this->cacheAction($feature->action());
        }
    }

    public function getActiveFeatures(): Collection
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return collect([new Features\MinecraftEula(), new Features\JavaVersion(), new Features\GSLToken(), new Features\PIDLimit(), new Features\SteamDiskSpace()])
            ->filter(fn (Feature $feature) => in_array($feature->featureName(), $server->egg->features));
    }

    #[On('line-to-check')]
    public function lineToCheck(string $line): void
    {
        /** @var Feature $feature */
        foreach ($this->getActiveFeatures() as $feature) {
            if ($feature->matchesListeners($line)) {
                usleep(2_000_000);
                $this->replaceMountedAction($feature->featureName());

                // $this->callMountedAction();
                // $a = $feature->action();
                // $this->cacheMountedFormComponentActionForm($a);
                // $this->formcomponentaction
                // $this->mountFormComponentAction($this->getId(), $feature->featureName());
                // logger()->info('Feature listens for this', compact(['feature', 'line']));

                // $this->mountAction($feature->featureName());
                // $this->dispatch('mountAction', action: $feature->featureName());

                // dd($this->getId());
                //$this->mountFormComponentAction('cool', $feature->featureName());
            }
        }
    }

    public function getWidgetData(): array
    {
        return [
            'server' => Filament::getTenant(),
            'user' => auth()->user(),
        ];
    }

    /** @var array<string, array<class-string<Widget>>> */
    protected static array $customWidgets = [];

    /** @param class-string<Widget>[] $customWidgets */
    public static function registerCustomWidgets(ConsoleWidgetPosition $position, array $customWidgets): void
    {
        static::$customWidgets[$position->value] = array_unique(array_merge(static::$customWidgets[$position->value] ?? [], $customWidgets));
    }

    /**
     * @return class-string<Widget>[]
     */
    public function getWidgets(): array
    {
        $allWidgets = [];

        $allWidgets = array_merge($allWidgets, static::$customWidgets[ConsoleWidgetPosition::Top->value] ?? []);

        $allWidgets[] = ServerOverview::class;

        $allWidgets = array_merge($allWidgets, static::$customWidgets[ConsoleWidgetPosition::AboveConsole->value] ?? []);

        $allWidgets[] = ServerConsole::class;

        $allWidgets = array_merge($allWidgets, static::$customWidgets[ConsoleWidgetPosition::BelowConsole->value] ?? []);

        $allWidgets = array_merge($allWidgets, [
            ServerCpuChart::class,
            ServerMemoryChart::class,
            //ServerNetworkChart::class, TODO: convert units.
        ]);

        $allWidgets = array_merge($allWidgets, static::$customWidgets[ConsoleWidgetPosition::Bottom->value] ?? []);

        return array_unique($allWidgets);
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
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isStartable())
                ->icon('tabler-player-play-filled'),
            Action::make('restart')
                ->color('gray')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'restart', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_RESTART, $server))
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isRestartable())
                ->icon('tabler-reload'),
            Action::make('stop')
                ->color('danger')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'stop', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->hidden(fn () => $this->status->isStartingOrStopping() || $this->status->isKillable())
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isStoppable())
                ->icon('tabler-player-stop-filled'),
            Action::make('kill')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Do you wish to kill this server?')
                ->modalDescription('This can result in data corruption and/or data loss!')
                ->modalSubmitActionLabel('Kill Server')
                ->size(ActionSize::ExtraLarge)
                ->action(fn () => $this->dispatch('setServerState', state: 'kill', uuid: $server->uuid))
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->hidden(fn () => $server->isInConflictState() || !$this->status->isKillable())
                ->icon('tabler-alert-square'),
        ];
    }
}
