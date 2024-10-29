<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid gap-y-2">
            <div class="flex items-center gap-x-2">
                <x-filament::icon-button icon="tabler-brand-docker" color="{{ $color }}" size="xl"/>
    
                <span class="text-2xl font-semibold text-gray-500 dark:text-gray-400">
                    {{ $name }} ({{ $uptime }})
                </span>

                <x-filament::button class="ml-auto" wire:click="openServer">
                    Open
                </x-filament::button>
            </div>
    
            <div class="flex">
                <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
                    <strong>CPU:</strong> {{ $cpu }}
                </div>
                <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
                    <strong>RAM:</strong> {{ $memory }}
                </div>
                <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
                    <strong>Disk:</strong> {{ $disk }}
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>