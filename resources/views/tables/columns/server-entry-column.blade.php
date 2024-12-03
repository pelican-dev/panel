<head>
    <style>
        .inline-flex {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        svg {
            width: 1.75rem;
            height: 1.75rem;
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
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icons-tabler-outline icon-tabler-egg">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M19 14.083c0 4.154 -2.966 6.74 -7 6.917c-4.2 0 -7 -2.763 -7 -6.917c0 -5.538 3.5 -11.09 7 -11.083c3.5 .007 7 5.545 7 11.083z" />
            </svg> {{ $getRecord()->egg->name }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
            </svg> {{ $getRecord()->user->username }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icons-tabler-outline icon-tabler-network">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M6 9a6 6 0 1 0 12 0a6 6 0 0 0 -12 0" />
                <path d="M12 3c1.333 .333 2 2.333 2 6s-.667 5.667 -2 6" />
                <path d="M12 3c-1.333 .333 -2 2.333 -2 6s.667 5.667 2 6" />
                <path d="M6 9h12" />
                <path d="M3 20h7" />
                <path d="M14 20h7" />
                <path d="M10 20a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path d="M12 15v3" />
            </svg> {{ $getRecord()->allocation->address }}
        </div>
    </div>


    <div class="flex">
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="1.25">
                <path d="M5 5m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z"></path>
                <path d="M9 9h6v6h-6z"></path>
                <path d="M3 10h2"></path>
                <path d="M3 14h2"></path>
                <path d="M10 3v2"></path>
                <path d="M14 3v2"></path>
                <path d="M21 10h-2"></path>
                <path d="M21 14h-2"></path>
                <path d="M14 21v-2"></path>
                <path d="M10 21v-2"></path>
                <title>CPU Usage</title>
            </svg>
            {{ $this->cpu($getRecord()) }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icons-tabler-outline icon-tabler-device-desktop-analytics">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M3 4m0 1a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1z" />
                <path d="M7 20h10" />
                <path d="M9 16v4" />
                <path d="M15 16v4" />
                <path d="M9 12v-4" />
                <path d="M12 12v-1" />
                <path d="M15 12v-2" />
                <path d="M12 12v-1" />
                <title>Memory Usage</title>
            </svg>{{ $this->memory($getRecord()) }}
        </div>
        <div class="flex-1 text-md font-medium text-gray-850 dark:text-white inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icons-tabler-outline icon-tabler-packages">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M7 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                <path d="M2 13.5v5.5l5 3" />
                <path d="M7 16.545l5 -3.03" />
                <path d="M17 16.5l-5 -3l5 -3l5 3v5.5l-5 3z" />
                <path d="M12 19l5 3" />
                <path d="M17 16.5l5 -3" />
                <path d="M12 13.5v-5.5l-5 -3l5 -3l5 3v5.5" />
                <path d="M7 5.03v5.455" />
                <path d="M12 8l5 -3" />
                <title>Disk Usage</title>
            </svg> {{ $this->disk($getRecord()) }}
        </div>
    </div>
</div>
