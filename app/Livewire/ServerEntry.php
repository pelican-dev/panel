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

    public function placeholder(): string
    {
        return <<<'HTML'
        <div class="relative">
            <div
                class="absolute left-0 top-1 bottom-0 w-1 rounded-lg"
                style="background-color: #D97706;">
            </div>

            <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-lg overflow-hidden p-3">
                <div class="flex items-center mb-5 gap-2">
                    <x-filament::loading-indicator class="h-5 w-5" />
                    <h2 class="text-xl font-bold">
                        {{ $server->name }}
                    </h2>
                </div>

                <div class="flex justify-between text-center">
                    <div>
                        <p class="text-sm dark:text-gray-400">CPU</p>
                        <p class="text-md font-semibold">{{ Number::format(0, precision: 2, locale: auth()->user()->language ?? 'en') . '%' }}</p>
                        <hr class="p-0.5">
                        <p class="text-xs dark:text-gray-400">{{ $server->formatResource('cpu', type: \App\Enums\ServerResourceType::Percentage, limit: true) }}</p>
                    </div>
                    <div>
                        <p class="text-sm dark:text-gray-400">Memory</p>
                        <p class="text-md font-semibold">{{ convert_bytes_to_readable(0, decimals: 2) }}</p>
                        <hr class="p-0.5">
                        <p class="text-xs dark:text-gray-400">{{ $server->formatResource('memory', limit: true) }}</p>
                    </div>
                    <div>
                        <p class="text-sm dark:text-gray-400">Disk</p>
                        <p class="text-md font-semibold">{{ convert_bytes_to_readable(0, decimals: 2) }}</p>
                        <hr class="p-0.5">
                        <p class="text-xs dark:text-gray-400">{{ $server->formatResource('disk', limit: true) }}</p>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm dark:text-gray-400">Network</p>
                        <hr class="p-0.5">
                        <p class="text-md font-semibold">{{ $server->allocation->address }} </p>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
