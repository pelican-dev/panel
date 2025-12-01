<?php

namespace App\Livewire;

use App\Filament\Server\Pages\Console;
use App\Models\Server;
use Filament\Support\Facades\FilamentView;
use Illuminate\View\View;
use Livewire\Component;

class ServerEntry extends Component
{
    public Server $server;

    public function render(): View
    {
        return view('livewire.server-entry', ['component' => $this]);
    }

    public function placeholder(): View
    {
        return view('livewire.server-entry-placeholder', ['server' => $this->server, 'component' => $this]);
    }

    public function redirectUrl(?bool $shouldOpenUrlInNewTab = false): string
    {
        $url = Console::getUrl(panel: 'server', tenant: $this->server);
        $target = $shouldOpenUrlInNewTab ? '_blank' : '_self';

        if (!$shouldOpenUrlInNewTab && FilamentView::hasSpaMode($url)) {
            return sprintf("Livewire.navigate('%s')", $url);
        }

        return sprintf("window.open('%s', '%s')", $url, $target);
    }
}
