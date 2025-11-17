<?php

namespace App\Livewire;

use App\Models\Server;
use Illuminate\View\View;
use Livewire\Component;

class ServerEntry extends Component
{
    public Server $server;

    public function render(): View
    {
        return view('livewire.server-entry');
    }

    public function placeholder(): View
    {
        return view('livewire.server-entry-placeholder', ['server' => $this->server]);
    }
}
