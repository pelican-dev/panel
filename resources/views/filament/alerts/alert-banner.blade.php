@props(['alertBanner'])

@isset ($alertBanner)
    @php
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
            "success" => "text-success-600 dark:text-success-500",
            "warning" => "text-warning-600 dark:text-warning-500",
            "danger" => "text-danger-600 dark:text-danger-500",
            default => "text-info-600 dark:text-info-500",
        };
    @endphp

    <div class="{{$colorClasses}} flex p-4 rounded-xl shadow-lg bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10">
        @if (filled($icon))
            <x-filament::icon :icon="$icon" class="h-8 w-8 mr-2" color="{{$status}}" />
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
