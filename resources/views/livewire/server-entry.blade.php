<div wire:poll.15s
     class="relative cursor-pointer"
     x-on:click="window.location.href = '{{ \App\Filament\Server\Pages\Console::getUrl(panel: 'server', tenant: $server) }}'">

    <div class="absolute left-0 top-1 bottom-0 w-1 rounded-lg"
         style="background-color: {{ $server->condition->getColor(true) }};">
    </div>

    <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-lg overflow-hidden p-3">
        <div class="flex items-center mb-5 gap-2">
            <div class="end-0" x-on:click.stop>
                <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-b-lg overflow-hidden p-1">
                    <x-filament-tables::actions
                        :actions="\App\Filament\App\Resources\ServerResource\Pages\ListServers::getPowerActions()"
                        :alignment="\Filament\Support\Enums\Alignment::Center"
                        :record="$server"
                    />
                </div>
            </div>

            <h2 class="text-xl font-bold">
                {{ $server->name }}
                <span class="dark:text-gray-400">
                    ({{ $server->formatResource('uptime', type: \App\Enums\ServerResourceType::Time) }})
                </span>
            </h2>
        </div>

        <div class="flex justify-between text-center items-center gap-4">
            <div>
                <p class="text-sm dark:text-gray-400">CPU</p>
                <p class="text-md font-semibold">{{ $server->formatResource('cpu_absolute', type: \App\Enums\ServerResourceType::Percentage) }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource('cpu', limit: true, type: \App\Enums\ServerResourceType::Percentage) }}</p>
            </div>
            <div>
                <p class="text-sm dark:text-gray-400">Memory</p>
                <p class="text-md font-semibold">{{ $server->formatResource('memory_bytes') }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource('memory', limit: true) }}</p>
            </div>
            <div>
                <p class="text-sm dark:text-gray-400">Disk</p>
                <p class="text-md font-semibold">{{ $server->formatResource('disk_bytes') }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource('disk', limit: true) }}</p>
            </div>
            <div class="hidden sm:block">
                <p class="text-sm dark:text-gray-400">Network</p>
                <hr class="p-0.5">
                <p class="text-md font-semibold">{{ $server->allocation?->address ?? 'None' }}</p>
            </div>
        </div>
    </div>
</div>
