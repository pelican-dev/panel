@php
/** @var \App\Models\Server $server */
$server = $getRecord();
@endphp

<head>
    <style>
        .inline-flex {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }
    </style>
</head>
<div class="w-full grid gap-y-2 overflow-x-auto">
    <div class="flex items-center gap-x-1">
        <x-filament::icon-button
            :icon="$server->conditionIcon()"
            :color="$server->conditionColor()"
            :tooltip="\Illuminate\Support\Str::title($server->condition)" size="xl"
        />

        <span class="text-2xl font-semibold text-gray-500 dark:text-gray-400">
            @php($uptime = $server->resources()['uptime'] ?? 0)
            {{ $server->name }}
            ({{ $uptime === 0 ? 'Offline' :
                now()->subMillis($uptime)->diffForHumans(syntax: \Carbon\CarbonInterface::DIFF_ABSOLUTE, short: true, parts: 2)
            }})
        </span>
    </div>

    <div class="flex">
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Egg Name">
            <x-filament::icon icon="tabler-egg" />
            {{ $server->egg->name }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Owner">
            <x-filament::icon icon="tabler-user" />
            {{ $server->user->username }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Primary Allocation">
            <x-filament::icon icon="tabler-network" />
            {{ $server->allocation->address }}
        </div>
    </div>


    <div class="flex">
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="CPU Usage">
            <x-filament::icon icon="tabler-cpu" />
            {{ Number::format($server->resources()['cpu_absolute'] ?? 0, maxPrecision: 2, locale: auth()->user()->language) . '%' }}
            @if ($server->cpu > 0)
                / {{ Number::format($server->cpu, locale: auth()->user()->language) . '%' }}
            @endif
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Memory Usage">
            <x-filament::icon icon="tabler-device-desktop-analytics" />
            {{ $this->memory($server) }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Disk Usage">
            <x-filament::icon icon="tabler-packages" />
             {{ $this->disk($server) }}
        </div>
    </div>
</div>
