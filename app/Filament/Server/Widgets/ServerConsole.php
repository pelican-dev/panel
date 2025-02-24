<?php

namespace App\Filament\Server\Widgets;

use App\Enums\ContainerStatus;
use App\Exceptions\Http\HttpForbiddenException;
use App\Livewire\AlertBanner;
use App\Models\Permission;
use App\Models\Server;
use App\Models\User;
use App\Services\Nodes\NodeJWTService;
use App\Services\Servers\GetUserPermissionsService;
use Filament\Widgets\Widget;
use Illuminate\Support\Arr;
use Illuminate\Support\Number;
use Livewire\Attributes\On;

class ServerConsole extends Widget
{
    protected static string $view = 'filament.components.server-console';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public ?Server $server = null;

    public ?User $user = null;

    public array $history = [];

    public int $historyIndex = 0;

    public string $input = '';

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
        return $this->authorizeSendCommand() && !$this->server->isInConflictState() && $this->server->retrieveStatus() === 'running';
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

    public function cpuUsage(): string
    {
        if (ContainerStatus::tryFrom($this->server->retrieveStatus()) === ContainerStatus::Offline) {
            return 'Offline';
        }

        $data = collect(cache()->get("servers.{$this->server->id}.cpu_absolute"))->last(default: 0);
        $cpu = Number::format($data, maxPrecision: 2, locale: auth()->user()->language) . ' %';

        return $cpu . ($this->server->cpu > 0 ? ' / ' . Number::format($this->server->cpu, locale: auth()->user()->language) . ' %' : ' / ∞');
    }

    public function memoryUsage(): string
    {
        if (ContainerStatus::tryFrom($this->server->retrieveStatus()) === ContainerStatus::Offline) {
            return 'Offline';
        }

        $latestMemoryUsed = collect(cache()->get("servers.{$this->server->id}.memory_bytes"))->last(default: 0);
        $totalMemory = collect(cache()->get("servers.{$this->server->id}.memory_limit_bytes"))->last(default: 0);
        $used = config('panel.use_binary_prefix')
            ? Number::format($latestMemoryUsed / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($latestMemoryUsed / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        $total = config('panel.use_binary_prefix')
            ? Number::format($totalMemory / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($totalMemory / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        return $used . ($this->server->memory > 0 ? ' / ' . $total : ' / ∞');
    }

    public function diskUsage(): string
    {
        $disk = collect(cache()->get("servers.{$this->server->id}.disk_bytes"))->last(default: 0);

        if ($disk === 0) {
            return 'Unavailable';
        }

        $used = config('panel.use_binary_prefix')
            ? Number::format($disk / 1024 / 1024 / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($disk / 1000 / 1000 / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';
        $total = config('panel.use_binary_prefix')
            ? Number::format($this->server->disk / 1024, maxPrecision: 2, locale: auth()->user()->language) .' GiB'
            : Number::format($this->server->disk / 1000, maxPrecision: 2, locale: auth()->user()->language) . ' GB';

        return $used . ($this->server->disk > 0 ? ' / ' . $total : ' / ∞');
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
