@props(['alertBanner'])

@php
    if ($alertBanner) {
        $status = $alertBanner['status'];

        $title = $alertBanner['title'];
        $body = $alertBanner['body'];

        $icon = $alertBanner['icon'] ?? match ($status) {
            "success" => "tabler-circle-check",
            "warning" => "tabler-exclamation-circle",
            "danger" => "tabler-circle-x",
            default => "tabler-info-circle",
        };

        $colorClasses = match ($status) {
            "success" => "text-success-500 border-success-500",
            "warning" => "text-warning-500 border-warning-500",
            "danger" => "text-danger-500 border-danger-500",
            default => "text-info-500 border-info-500",
        };
    }
@endphp

@isset ($alertBanner)
    <div
        class="{{$colorClasses}} flex p-4 rounded-xl shadow-md bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10">
        @if (filled($icon))
            <x-filament::icon :icon="$icon" class="max-h-10 mr-2" color="{{$status}}" />
        @endif

        <div class="flex flex-col flex-grow">
            @if (filled($title))
                <p class="font-bold">{{str($title)->sanitizeHtml()->toHtmlString()}}</p>
            @endif

            @if (filled($body))
                <p class="font-normal">{{str($body)->sanitizeHtml()->toHtmlString()}}</p>
            @endif
        </div>
    </div>
@endisset
