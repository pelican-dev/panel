<div class="relative">
    <div
        class="absolute left-0 top-1 bottom-0 w-1 rounded-lg"
        style="background-color: {{ $server->condition->getColor(true) }};">
    </div>

    <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-lg overflow-hidden p-3">
        <div class="flex items-center mb-5 gap-2">
            <x-filament::icon-button
                :icon="$server->condition->getIcon()"
                :color="$server->condition->getColor()"
                :tooltip="$server->condition->getLabel()"
                size="xl"
            />
            <h2 class="text-xl font-bold">
                {{ $server->name }}
                <span class="dark:text-gray-400">({{ $server->formatResource('uptime', type: \App\Enums\ServerResourceType::Time) }})</span>
            </h2>
        </div>

        <div class="flex justify-between text-center">
            <div>
                <p class="text-sm dark:text-gray-400">CPU</p>
                <p class="text-md font-semibold">{{ $server->formatResource('cpu_absolute', type: \App\Enums\ServerResourceType::Percentage) }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource('cpu', type: \App\Enums\ServerResourceType::Percentage, limit: true) }}</p>
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
                <p class="text-md font-semibold">{{ $server->allocation->address }} </p>
            </div>
        </div>
    </div>
</div>