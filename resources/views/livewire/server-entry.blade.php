<div wire:poll.15s
     class="relative cursor-pointer"
     x-on:click="window.location.href = '{{ \App\Filament\Server\Pages\Console::getUrl(panel: 'server', tenant: $server) }}'">

    <div class="absolute left-0 top-1 bottom-0 w-1 rounded-lg"
         style="background-color: {{ $server->condition->getColor(true) }};">
    </div>

    <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-lg overflow-hidden p-3">
        <div class="flex items-center mb-5 gap-2">
            <x-filament::icon-button
                :icon="$server->condition->getIcon()"
                :color="$server->condition->getColor()"
                :tooltip="$server->condition->getLabel()"
                size="lg"
            />
            <h2 class="text-xl font-bold">
                {{ $server->name }}
                <span class="dark:text-gray-400">
                    ({{ $server->formatResource(\App\Enums\ServerResourceType::Uptime) }})
                </span>
            </h2>
            <div class="end-0" x-on:click.stop>
                <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-b-lg overflow-hidden p-1">
                    <x-filament-tables::actions
                        :actions="\App\Filament\App\Resources\ServerResource\Pages\ListServers::getPowerActions(view: 'grid')"
                        :alignment="\Filament\Support\Enums\Alignment::Center"
                        :record="$server"
                    />
                </div>
            </div>
        </div>

        <div class="flex justify-between text-center items-center gap-4">
            <div>
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.cpu') }}</p>
                <p class="text-md font-semibold">{{ $server->formatResource(\App\Enums\ServerResourceType::CPU) }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::CPULimit) }}</p>
            </div>
            <div>
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.memory') }}</p>
                <p class="text-md font-semibold">{{ $server->formatResource(\App\Enums\ServerResourceType::Memory) }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::MemoryLimit) }}</p>
            </div>
            <div>
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.disk') }}</p>
                <p class="text-md font-semibold">{{ $server->formatResource(\App\Enums\ServerResourceType::Disk) }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::DiskLimit) }}</p>
            </div>
            <div class="hidden sm:block">
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.network') }}</p>
                <hr class="p-0.5">
                <p class="text-md font-semibold">{{ $server->allocation?->address ?? trans('server/dashboard.none') }}</p>
            </div>
        </div>
    </div>
</div>
