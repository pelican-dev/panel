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
                    <x-filament::loading-indicator class="h-6 w-6" />
                    <h2 class="text-xl font-bold">
                        {{ $server->name }}
                        <span class="dark:text-gray-400">
                        ({{ trans('server/dashboard.loading') }})
                        </span>
                    </h2>
                </div>

                <div class="flex justify-between text-center items-center gap-4">
                    <div>
                        <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.cpu') }}</p>
                        <p class="text-md font-semibold">{{ Number::format(0, precision: 2, locale: auth()->user()->language ?? 'en') . '%' }}</p>
                        <hr class="p-0.5">
                        <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::CPULimit) }}</p>
                    </div>
                    <div>
                        <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.memory') }}</p>
                        <p class="text-md font-semibold">{{ convert_bytes_to_readable(0, decimals: 2) }}</p>
                        <hr class="p-0.5">
                        <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::MemoryLimit) }}</p>
                    </div>
                    <div>
                        <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.disk') }}</p>
                        <p class="text-md font-semibold">{{ convert_bytes_to_readable(0, decimals: 2) }}</p>
                        <hr class="p-0.5">
                        <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::DiskLimit) }}</p>
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.network') }}</p>
                        <hr class="p-0.5">
                        <p class="text-md font-semibold">{{ $server->allocation?->address ?? trans('server/dashboard.none') }} </p>
                    </div>
                </div>
            </div>
        </div>
        HTML;
    }
}
