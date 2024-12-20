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
            :icon="$getRecord()->conditionIcon()"
            :color="$getRecord()->conditionColor()"
            :tooltip="\Illuminate\Support\Str::title($getRecord()->condition)" size="xl"
        />

        <span class="text-2xl font-semibold text-gray-500 dark:text-gray-400">
            {{ $getRecord()->name }} ({{ $this->uptime($getRecord()) }})
        </span>
    </div>

    <div class="flex">
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Egg Name">
            <x-filament::icon
                icon="tabler-egg"
            />
            {{ $getRecord()->egg->name }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Owner">
            <x-filament::icon
                icon="tabler-user"
            />
            {{ $getRecord()->user->username }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Primary Allocation">
            <x-filament::icon
                icon="tabler-network"
            />
            {{ $getRecord()->allocation->address }}
        </div>
    </div>


    <div class="flex">
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="CPU Usage">
            <x-filament::icon
                icon="tabler-cpu"
            />
            {{ $this->cpu($getRecord()) }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Memory Usage">
            <x-filament::icon
                icon="tabler-device-desktop-analytics"
            />
            {{ $this->memory($getRecord()) }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex" title="Disk Usage">
            <x-filament::icon
                icon="tabler-packages"
            />
             {{ $this->disk($getRecord()) }}
        </div>
    </div>
</div>
