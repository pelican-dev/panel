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
            style="background-color: {{ $getRecord()->conditionColorHex() }};">
        </div>

        <!-- Card Component -->
        <div class="flex-1 bg-gray-800 text-white rounded-lg overflow-hidden p-3">
            <!-- Header -->
            <div class="flex items-center mb-3 ml-1">
                <div class="flex items-center gap-2">
                    <x-filament::icon-button
                        :icon="$getRecord()->conditionIcon()"
                        :color="$getRecord()->conditionColor()"
                        :tooltip="\Illuminate\Support\Str::title($getRecord()->condition)" size="xl"
                    />
                    <h2 class="text-xl font-bold">
                        {{ $getRecord()->name }}
                        <span class="text-gray-400">({{ $this->uptime($getRecord()) }})</span>
                    </h2>
                </div>
            </div>

            <!-- Resource Usage -->
            <div class="flex justify-between text-center">
                <div>
                    <p class="text-sm text-gray-400">CPU</p>
                    <p class="text-md font-semibold">{{ $this->cpu($getRecord()) }}</p>
                    <hr class="p-0.5">
                    <p class="text-xs text-gray-400">{{ $this->cpuLimit($getRecord()) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Memory</p>
                    <p class="text-md font-semibold">{{ $this->memory($getRecord()) }}</p>
                    <hr class="p-0.5">
                    <p class="text-xs text-gray-400">{{ $this->memoryLimit($getRecord()) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Disk</p>
                    <p class="text-md font-semibold">{{ $this->disk($getRecord()) }}</p>
                    <hr class="p-0.5">
                    <p class="text-xs text-gray-400">{{ $this->diskLimit($getRecord()) }}</p>
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm text-gray-400">Network</p>
                    <p class="text-md font-semibold">{{ $getRecord()->allocation->address }} </p>
                </div>
            </div>
        </div>
    </div>
</div>
