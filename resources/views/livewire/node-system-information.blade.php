<div wire:poll.10s>
    @php
        $exception = $node->systemInformation()['exception'] ?? null;
        $version = $node->systemInformation()['version'] ?? null;
        $content = $exception ? 'Error connecting to node!<br>Check browser console for details.' : $version;
        $icon = 'tabler-heart' . ($exception ? '-off' : 'beat');
        $animated = $exception ? '' : 'animate-pulse';
        $condition = $exception ? 'danger' : 'success';
        $class = ['fi-ta-icon-item', $sizeClasses, 'fi-color-custom text-custom-500 dark:text-custom-400', 'fi-color-'.$condition, $animated];
    @endphp
    <x-filament::icon
        x-tooltip="{
                content: '{{ $content }}',
                theme: $store.theme,
                allowHTML: true,
                placement: 'bottom',
            }"
        :icon='$icon'
        @class($class)
    />
    @if($exception)
        @script
        <script>
            console.error('{{ $exception }} ');
        </script>
        @endscript
    @endif
</div>

