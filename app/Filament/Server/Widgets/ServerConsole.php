<?php

namespace App\Filament\Server\Widgets;

use App\Exceptions\Http\HttpForbiddenException;
use App\Features\Feature;
use App\Livewire\AlertBanner;
use App\Models\Permission;
use App\Models\Server;
use App\Models\User;
use App\Services\Nodes\NodeJWTService;
use App\Services\Servers\GetUserPermissionsService;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Widgets\Widget;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use App\Features;
use App\Features\CustomModal;
use Filament\Forms\Components\TextInput;

class ServerConsole extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.components.server-console';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public ?Server $server = null;

    public ?User $user = null;

    /** @var string[] */
    public array $history = [];

    public int $historyIndex = 0;

    public string $input = '';

    public function modal(): CustomModal
    {
        return CustomModal::make('modal-eula')
            ->heading('Info!')
            ->description('Description')
            ->registerActions([/* if neccessary */]);
    }

    protected function getUserModal(): Form
    {
        return $this->makeForm()
            ->schema([
                Placeholder::make('see me'),
                TextInput::make('name'),
                Actions::make([
                    Actions\Action::make('closeUserModal')
                        ->label('Close')
                        ->color('secondary')
                        ->extraAttributes([
                            'x-on:click' => 'isOpen = false',  // close modal [FASTER]
                        ]),
                    Actions\Action::make('saveUserModal')
                        ->label('Save')
                        ->color('primary')
                        ->action(function (Get $get) {
                            logger($get('name'));
                        }),
                ])->fullWidth(),
            ]);
    }

    public function getModals(): array
    {
        $modals = [];
        foreach ($this->getActiveFeatures() as $feature) {
            $modals[] = $feature->modal();
        }

        return $modals;
    }

    private GetUserPermissionsService $getUserPermissionsService;

    private NodeJWTService $nodeJWTService;

    public function boot(GetUserPermissionsService $getUserPermissionsService, NodeJWTService $nodeJWTService): void
    {
        $this->getUserPermissionsService = $getUserPermissionsService;
        $this->nodeJWTService = $nodeJWTService;
    }

    protected function getToken(): string
    {
        if (!$this->user || !$this->server || $this->user->cannot(Permission::ACTION_WEBSOCKET_CONNECT, $this->server)) {
            throw new HttpForbiddenException('You do not have permission to connect to this server\'s websocket.');
        }

        $permissions = $this->getUserPermissionsService->handle($this->server, $this->user);

        return $this->nodeJWTService
            ->setExpiresAt(now()->addMinutes(10)->toImmutable())
            ->setUser($this->user)
            ->setClaims([
                'server_uuid' => $this->server->uuid,
                'permissions' => $permissions,
            ])
            ->handle($this->server->node, $this->user->id . $this->server->uuid)->toString();
    }

    protected function getSocket(): string
    {
        $socket = str_replace(['https://', 'http://'], ['wss://', 'ws://'], $this->server->node->getConnectionAddress());
        $socket .= sprintf('/api/servers/%s/ws', $this->server->uuid);

        return $socket;
    }

    protected function authorizeSendCommand(): bool
    {
        return $this->user->can(Permission::ACTION_CONTROL_CONSOLE, $this->server);
    }

    protected function canSendCommand(): bool
    {
        return $this->authorizeSendCommand() && !$this->server->isInConflictState() && $this->server->retrieveStatus()->isStartingOrRunning();
    }

    public function up(): void
    {
        $this->historyIndex = min($this->historyIndex + 1, count($this->history) - 1);

        $this->input = $this->history[$this->historyIndex] ?? '';
    }

    public function down(): void
    {
        $this->historyIndex = max($this->historyIndex - 1, -1);

        $this->input = $this->history[$this->historyIndex] ?? '';
    }

    public function enter(): void
    {
        if (!empty($this->input) && $this->canSendCommand()) {
            $this->dispatch('sendServerCommand', command: $this->input);

            $this->history = Arr::prepend($this->history, $this->input);
            $this->historyIndex = -1;

            $this->input = '';
        }
    }

    #[On('token-request')]
    public function tokenRequest(): void
    {
        $this->dispatch('sendAuthRequest', token: $this->getToken());
    }

    #[On('store-stats')]
    public function storeStats(string $data): void
    {
        $data = json_decode($data);

        $timestamp = now()->getTimestamp();

        foreach ($data as $key => $value) {
            $cacheKey = "servers.{$this->server->id}.$key";
            $data = cache()->get($cacheKey, []);

            $data[$timestamp] = $value;

            cache()->put($cacheKey, $data, now()->addMinute());
        }
    }

    /**
     * @return Feature[]
     */
    public function getActiveFeatures(): array
    {
        return [new Features\MinecraftEula(), new Features\JavaVersion()];
    }

    #[On('line-to-check')]
    public function lineToCheck(string $line): void
    {
        foreach ($this->getActiveFeatures() as $feature) {
            if ($feature->matchesListeners($line)) {
                logger()->info('Feature listens for this', compact(['feature', 'line']));

                // $this->dispatch('open-modal', id: "modal-{$feature->featureName()}");
                $this->dispatch('open-modal', id: 'edit-user');
            }
        }
    }

    #[On('websocket-error')]
    public function websocketError(): void
    {
        AlertBanner::make()
            ->title('Could not connect to websocket!')
            ->body('Check your browser console for more details.')
            ->danger()
            ->send();
    }
}
