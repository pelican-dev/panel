<?php

namespace App\Filament\Server\Widgets;

use App\Models\Server;
use App\Models\User;
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
        if (!empty($this->input)) {
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
