@php
    $backgroundImage = $server->icon ?? $server->egg->image;
@endphp

<div class="relative cursor-pointer"
     x-on:click="{{ $component->redirectUrl() }}"
     x-on:auxclick.prevent="if ($event.button === 1) {{ $component->redirectUrl(true) }}">
    <div class="absolute left-0 top-1 bottom-0 w-1 rounded-lg" style="background-color: #D97706;"></div>

    <div class="flex-1 dark:bg-gray-800 dark:text-white rounded-lg overflow-hidden p-3">
        @if($backgroundImage)
            <div style="
                position: absolute;
                inset: 0;
                background: url('{{ $backgroundImage }}') right no-repeat;
                background-size: contain;
                opacity: 0.20;
                max-width: 680px;
                max-height: 140px;
            "></div>
        @endif

        <div @class([
            'flex items-center gap-2',
            'mb-5' => !$server->description,
            ])>

            <x-filament::loading-indicator class="h-6 w-6" />
            <h2 class="text-xl font-bold">
                {{ $server->name }}
                <span class="dark:text-gray-400">({{ trans('server/dashboard.loading') }})</span>
            </h2>
        </div>

        @if ($server->description)
            <div class="text-left mb-1 ml-4 pl-4">
                <p class="text-base dark:text-gray-400">{{ Str::limit($server->description, 40, preserveWords: true) }}</p>
            </div>
        @endif


        <div class="flex justify-between text-center items-center gap-4">
            <div>
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.cpu') }}</p>
                <p class="text-md font-semibold">{{ format_number(0, precision: 2) . '%' }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::CPULimit) }}</p>
            </div>
            <div>
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.memory') }}</p>
                <p class="text-md font-semibold">{{ convert_bytes_to_readable(0, decimals: 2) }}</p>
                <hr class="p-0.5">
                <p class="text-xs dark:text-gray-400">{{ $server->formatResource(\App\Enums\ServerResourceType::MemoryLimit) }}</p>
            </div>
            <div>
                <p class="text-sm dark:text-gray-400">{{ trans('server/dashboard.disk') }}</p>
                <p class="text-md font-semibold">{{ convert_bytes_to_readable(0, decimals: 2) }}</p>
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

