@php
    $icon = $getIcon();
    $title = $getTitle();
    $body = $getBody();
@endphp

<div class="{{$getColorClasses()}} flex p-4 mt-3 rounded-xl shadow-lg bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10">
    @if (filled($icon))
        <x-filament::icon :icon="$icon" class="h-8 w-8 mr-2" color="{{$getStatus()}}" />
    @endif

    <div class="flex flex-col flex-grow">
        @if (filled($title))
            <p class="font-bold">{{str($title)->sanitizeHtml()->toHtmlString()}}</p>
        @endif

        @if (filled($body))
            <p class="font-normal">{{str($body)->sanitizeHtml()->toHtmlString()}}</p>
        @endif
    </div>

    @if ($isCloseable())
        <x-filament::icon-button color="gray" icon="tabler-x" wire:click="remove('{{$getId()}}')" />
    @endif
</div>
