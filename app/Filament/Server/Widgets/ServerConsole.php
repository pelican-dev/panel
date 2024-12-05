<?php

namespace App\Filament\Server\Widgets;

use App\Exceptions\Http\HttpForbiddenException;
use App\Models\Permission;
use App\Models\Server;
use App\Models\User;
use App\Services\Nodes\NodeJWTService;
use App\Services\Servers\GetUserPermissionsService;
use Filament\Widgets\Widget;
use Illuminate\Support\Arr;
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

    protected function getToken(): string
    {
        if (!$this->user || !$this->server || $this->user->cannot(Permission::ACTION_WEBSOCKET_CONNECT, $this->server)) {
            throw new HttpForbiddenException('You do not have permission to connect to this server\'s websocket.');
        }
        // @phpstan-ignore-next-line
        $permissions = app(GetUserPermissionsService::class)->handle($this->server, $this->user);

        // @phpstan-ignore-next-line
        return app(NodeJWTService::class)
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

    protected function canSendCommand(): bool
    {
        return !$this->server->isInConflictState() && $this->server->retrieveStatus() === 'running';
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

    #[On('storeStats')]
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
}
