<?php

namespace App\Livewire;

use App\Models\Server;
use Filament\Facades\Filament;
use Livewire\Attributes\On;
use Livewire\Component;

class ServerConflictBanner extends Component
{
    public ?Server $server = null;

    public function mount(): void
    {
        $this->server = Filament::getTenant();
    }

    #[On('power-changed')]
    public function refresh(): void
    {
        $this->server->fresh();
    }

    public function render()
    {
        return view('livewire.server-conflict-banner');
    }
}
