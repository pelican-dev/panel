<?php

namespace App\Filament\Server\Pages;

use App\Enums\ConsoleWidgetPosition;
use App\Enums\ContainerStatus;
use App\Exceptions\Http\Server\ServerStateConflictException;
use App\Extensions\Features\FeatureService;
use App\Filament\Server\Widgets\ServerConsole;
use App\Filament\Server\Widgets\ServerCpuChart;
use App\Filament\Server\Widgets\ServerMemoryChart;
use App\Filament\Server\Widgets\ServerNetworkChart;
use App\Filament\Server\Widgets\ServerOverview;
use App\Livewire\AlertBanner;
use App\Models\Permission;
use App\Models\Server;
use App\Traits\Filament\CanCustomizeHeaderActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Facades\Filament;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Livewire\Attributes\On;

class Console extends Page
{
    use CanCustomizeHeaderActions;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'tabler-brand-tabler';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.server.pages.console';

    public ContainerStatus $status = ContainerStatus::Offline;

    protected FeatureService $featureService;

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

    public function boot(FeatureService $featureService): void
    {
        $this->featureService = $featureService;
        /** @var Server $server */
        $server = Filament::getTenant();
        foreach ($featureService->getActiveSchemas($server->egg->features) as $feature) {
            $this->cacheAction($feature->getAction());
        }
    }

    #[On('mount-feature')]
    public function mountFeature(string $data): void
    {
        $data = json_decode($data);
        $feature = data_get($data, 'key');

        $feature = $this->featureService->get($feature);
        if (!$feature || $this->getMountedAction()) {
            return;
        }
        $this->mountAction($feature->getId());
        sleep(2); // TODO find a better way
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
            ServerNetworkChart::class,
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
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($state) {
            $this->status = ContainerStatus::from($state);
            cache()->put("servers.$server->uuid.status", $this->status, now()->addSeconds(15));
        }

        $this->cachedHeaderActions = [];

        $this->cacheHeaderActions();
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            Action::make('start')
                ->label(trans('server/console.power_actions.start'))
                ->color('primary')
                ->size(ActionSize::ExtraLarge)
                ->dispatch('setServerState', ['state' => 'start', 'uuid' => $server->uuid])
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_START, $server))
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isStartable())
                ->icon('tabler-player-play-filled'),
            Action::make('restart')
                ->label(trans('server/console.power_actions.restart'))
                ->color('gray')
                ->size(ActionSize::ExtraLarge)
                ->dispatch('setServerState', ['state' => 'restart', 'uuid' => $server->uuid])
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_RESTART, $server))
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isRestartable())
                ->icon('tabler-reload'),
            Action::make('stop')
                ->label(trans('server/console.power_actions.stop'))
                ->color('danger')
                ->size(ActionSize::ExtraLarge)
                ->dispatch('setServerState', ['state' => 'stop', 'uuid' => $server->uuid])
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->hidden(fn () => $this->status->isStartingOrStopping() || $this->status->isKillable())
                ->disabled(fn () => $server->isInConflictState() || !$this->status->isStoppable())
                ->icon('tabler-player-stop-filled'),
            Action::make('kill')
                ->label(trans('server/console.power_actions.kill'))
                ->color('danger')
                ->tooltip(trans('server/console.power_actions.kill_tooltip'))
                ->size(ActionSize::ExtraLarge)
                ->dispatch('setServerState', ['state' => 'kill', 'uuid' => $server->uuid])
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                ->hidden(fn () => $server->isInConflictState() || !$this->status->isKillable())
                ->icon('tabler-alert-square'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/console.title');
    }

    public function getTitle(): string
    {
        return trans('server/console.title');
    }
}
