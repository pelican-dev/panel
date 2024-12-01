<div class="w-full grid gap-y-2">
    <div class="flex items-center gap-x-2">
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
        <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
            <strong>Allocation:</strong> {{ $getRecord()->allocation->address }}
        </div>
        <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
            <strong>Egg:</strong> {{ $getRecord()->egg->name }}
        </div>
        <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
            <strong>Owner:</strong> {{ $getRecord()->user->username }}
        </div>
    </div>

    <div class="flex">
        <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
            <strong>CPU:</strong> {{ $this->cpu($getRecord()) }}
        </div>
        <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
            <strong>RAM:</strong> {{ $this->memory($getRecord()) }}
        </div>
        <div class="flex-1 text-sm font-medium text-gray-850 dark:text-white">
            <strong>Disk:</strong> {{ $this->disk($getRecord()) }}
        </div>
    </div>
</div>
