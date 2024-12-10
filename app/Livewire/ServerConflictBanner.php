<?php

namespace App\Livewire;

use App\Models\Server;
use Filament\Facades\Filament;
use Illuminate\Support\Sleep;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ServerConflictBanner extends Component
{
    public ?Server $server = null;

    public function mount(): void
    {
        /** @var Server $server */
        $server = Filament::getTenant();
        $this->server = $server;
    }

    #[On('power-changed')]
    public function refresh(): void
    {
        $secondsToKeepRefreshing = 5;
        for ($i = 0; $i < $secondsToKeepRefreshing; $i++) {
            $serverState = $this->server->status;
            $this->server->fresh();

            // If we find what we're looking for, break early
            if ($serverState !== $this->server->status) {
                break;
            }

            Sleep::sleep(1);
        }
    }

    public function render(): View
    {
        return view('livewire.server-conflict-banner');
    }
}
