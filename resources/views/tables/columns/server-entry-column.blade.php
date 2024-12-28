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
    <!-- Wrapper for Positioning -->
    <div class="relative flex">
        <!-- Status Strip Outside the Box -->
        <div
            class="absolute left-0 top-1 bottom-0 w-1 rounded-lg"
            style="background-color: {{ $server->conditionColorHex() }};">
        </div>

        <!-- Card Component -->
        <div class="flex-1 bg-gray-800 text-white rounded-lg overflow-hidden p-3">
            <!-- Header -->
            <div class="flex items-center mb-5 gap-2">
                <x-filament::icon-button
                    :icon="$server->conditionIcon()"
                    :color="$server->conditionColor()"
                    :tooltip="\Illuminate\Support\Str::title($server->condition)"
                    size="xl"
                />
                <h2 class="text-xl font-bold">
                    {{ $server->name }}
                    <span class="text-gray-400">({{ $server->formatResource('uptime', type: ServerResourceType::Time) }})</span>
                </h2>
            </div>

            <!-- Resource Usage -->
            <div class="flex justify-between text-center">
                <div>
                    <p class="text-sm text-gray-400">CPU</p>
                    <p class="text-md font-semibold">{{ $server->formatResource('cpu_absolute', type: ServerResourceType::Percentage) }}</p>
                    <hr class="p-0.5">
                    <p class="text-xs text-gray-400">{{ $server->formatResource('cpu', type: ServerResourceType::Percentage, limit: true) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Memory</p>
                    <p class="text-md font-semibold">{{ $server->formatResource('memory_bytes') }}</p>
                    <hr class="p-0.5">
                    <p class="text-xs text-gray-400">{{ $server->formatResource('memory', limit: true) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Disk</p>
                    <p class="text-md font-semibold">{{ $server->formatResource('disk_bytes') }}</p>
                    <hr class="p-0.5">
                    <p class="text-xs text-gray-400">{{ $server->formatResource('disk', limit: true) }}</p>
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm text-gray-400">Network</p>
                    <p class="text-md font-semibold">{{ $server->allocation->address }} </p>
                </div>
            </div>
        </div>
    </div>
</div>
