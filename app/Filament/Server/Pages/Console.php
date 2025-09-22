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
use Filament\Schemas\Components\Concerns\HasHeaderActions;
use Filament\Support\Enums\Size;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Livewire\Attributes\On;

class Console extends Page
{
    use CanCustomizeHeaderActions, HasHeaderActions {
        CanCustomizeHeaderActions::getHeaderActions insteadof HasHeaderActions;
    }
    use InteractsWithActions;

    protected static ?int $navigationSort = 1;

    protected static string|\BackedEnum|null $navigationIcon = 'tabler-brand-tabler';

    protected string $view = 'filament.server.pages.console';

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

        $this->headerActions($this->getHeaderActions());
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('start')
                    ->label(trans('server/console.power_actions.start'))
                    ->color('primary')
                    ->icon('tabler-player-play-filled')
                    ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_START, $server))
                    ->disabled(fn (Server $server) => $server->isInConflictState() || !$this->status->isStartable())
                    ->action(fn (Server $server) => $this->dispatch('setServerState', uuid: $server->uuid, state: 'start'))
                    ->size(Size::ExtraLarge),
                Action::make('restart')
                    ->label(trans('server/console.power_actions.restart'))
                    ->color('gray')
                    ->icon('tabler-reload')
                    ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_RESTART, $server))
                    ->disabled(fn (Server $server) => $server->isInConflictState() || !$this->status->isRestartable())
                    ->action(fn (Server $server) => $this->dispatch('setServerState', uuid: $server->uuid, state: 'restart'))
                    ->size(Size::ExtraLarge),
                Action::make('stop')
                    ->label(trans('server/console.power_actions.stop'))
                    ->color('danger')
                    ->icon('tabler-player-stop-filled')
                    ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                    ->visible(fn () => !$this->status->isKillable())
                    ->disabled(fn (Server $server) => $server->isInConflictState() || !$this->status->isStoppable())
                    ->action(fn (Server $server) => $this->dispatch('setServerState', uuid: $server->uuid, state: 'stop'))
                    ->size(Size::ExtraLarge),
                Action::make('kill')
                    ->label(trans('server/console.power_actions.kill'))
                    ->color('danger')
                    ->icon('tabler-alert-square')
                    ->tooltip(trans('server/console.power_actions.kill_tooltip'))
                    ->requiresConfirmation()
                    ->authorize(fn (Server $server) => auth()->user()->can(Permission::ACTION_CONTROL_STOP, $server))
                    ->visible(fn () => $this->status->isKillable())
                    ->disabled(fn (Server $server) => $server->isInConflictState() || !$this->status->isKillable())
                    ->action(fn (Server $server) => $this->dispatch('setServerState', uuid: $server->uuid, state: 'kill'))
                    ->size(Size::ExtraLarge),
            ])
                ->record(function () {
                    /** @var Server $server */
                    $server = Filament::getTenant();

                    return $server;
                })
                ->buttonGroup(),
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
