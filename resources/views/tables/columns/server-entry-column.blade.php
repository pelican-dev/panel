@php
    use App\Enums\ServerResourceType;

    /** @var \App\Models\Server $server */
    $server = $getRecord();
@endphp

<head>
    <style>
        hr {
            border-color: #9ca3af;
        }
    </style>
</head>
<div class="w-full">
    <div class="relative">
        <div
            class="absolute left-0 top-1 bottom-0 w-1 rounded-lg"
            style="background-color: {{ $server->condition->getColor(true) }};">
        </div>

        <div class="bg-gray-800 dark:text-white overflow-hidden p-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <x-filament::icon-button
                        :icon="$server->condition->getIcon()"
                        :color="$server->condition->getColor()"
                        :tooltip="$server->condition->getLabel()"
                        size="xl"
                    />
                    <h2 class="text-2xl font-semibold p-2">
                        {{ $server->name }}
                        <span class="dark:text-gray-400">
                        ({{ $server->formatResource('uptime', type: ServerResourceType::Time) }})
                        </span>
                    </h2>
                </div>

                <div class="flex w-1/2 justify-between text-center">
                    <div class="w-1/4">
                        <p class="text-md dark:text-gray-400">CPU</p>
                        <hr class="p-0.5">
                        <p class="text-md font-semibold">
                            {{ $server->formatResource('cpu_absolute', type: ServerResourceType::Percentage) }}
                        </p>
                    </div>
                    <div class="w-1/4">
                        <p class="text-md dark:text-gray-400">Memory</p>
                        <hr class="p-0.5">
                        <p class="text-md font-semibold">
                            {{ $server->formatResource('memory_bytes') }}
                        </p>
                    </div>
                    <div class="w-1/4 hidden sm:block">
                        <p class="text-md dark:text-gray-400">Disk</p>
                        <hr class="p-0.5">
                        <p class="text-md font-semibold">
                            {{ $server->formatResource('disk_bytes') }}
                        </p>
                    </div>
                    <div class="w-1/4 hidden sm:block">
                        <p class="text-md dark:text-gray-400">Network</p>
                        <hr class="p-0.5">
                        <p class="text-md font-semibold">
                            {{ $server->allocation->address }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



