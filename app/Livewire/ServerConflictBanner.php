<?php

namespace App\Livewire;

use App\Models\Server;
use Filament\Facades\Filament;
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

    #[On('console-install-completed')]
    #[On('console-install-started')]
    #[On('console-status')]
    public function refresh(?string $state = null): void
    {
        $this->server->fresh();
    }

    public function render(): View
    {
        return view('livewire.server-conflict-banner');
    }
}
