@php
    $icon = $getIcon();
    $status = $getStatus() ?? 'info';
    $title = $getTitle();
    $body = $getBody();
@endphp

<x-filament::callout
    :icon="$icon"
    :color="$status"
>
    @if (filled($title))
        <x-slot name="heading">
            {{str($title)->sanitizeHtml()->toHtmlString()}}
        </x-slot>
    @endif

    @if (filled($body))
        <x-slot name="description">
            {{str($body)->sanitizeHtml()->toHtmlString()}}
        </x-slot>
    @endif
    
    @if ($isCloseable())
        <x-slot name="controls">
            <x-filament::icon-button color="gray" icon="tabler-x" wire:click="remove('{{$getId()}}')" />
        </x-slot>
    @endif
</x-filament::callout>
