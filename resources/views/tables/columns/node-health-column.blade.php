@php
    use Filament\Tables\Columns\IconColumn\IconColumnSize;

    $node = $getRecord();
    $size = $getSize($node) ?? IconColumnSize::Large;

    $sizeClasses = match ($size) {
        IconColumnSize::ExtraSmall, 'xs' => 'fi-ta-icon-item-size-xs h-3 w-3',
        IconColumnSize::Small, 'sm' => 'fi-ta-icon-item-size-sm h-4 w-4',
        IconColumnSize::Medium, 'md' => 'fi-ta-icon-item-size-md h-5 w-5',
        IconColumnSize::Large, 'lg' => 'fi-ta-icon-item-size-lg h-6 w-6',
        IconColumnSize::ExtraLarge, 'xl' => 'fi-ta-icon-item-size-xl h-7 w-7',
        IconColumnSize::TwoExtraLarge, IconColumnSize::ExtraExtraLarge, '2xl' => 'fi-ta-icon-item-size-2xl h-8 w-8',
        default => $size,
    }
@endphp
<div
    {{
        $attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-ta-icon flex gap-1.5',
                'flex-wrap' => $canWrap(),
                'px-3 py-4' => ! $isInline(),
                'flex-col' => $isListWithLineBreaks(),
            ])
    }}
>
    <div wire:poll.10s>
        @php
            $exception = $node->systemInformation()['exception'] ?? null;
            $version = $node->systemInformation()['version'] ?? null;
            $icon = 'tabler-heart' . ($version ? 'beat' : '-off');
            $content = $version ?? 'Error connecting to node!<br>Check browser console for details.';
            $condition = $version ? 'success' : 'danger';
            $animated = $version ? ' animate-pulse' : '';
            $class = ['fi-ta-icon-item', $sizeClasses, 'fi-color-custom text-custom-500 dark:text-custom-400', 'fi-color-'.$condition.$animated];
            $style = [Filament\Support\get_color_css_variables($condition, shades: [400, 500], alias: 'tables::columns.icon-column.item')];
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
            @style($style)
        />
        @if($exception)
            @script
            <script>
                console.error('{{ $exception }} ');
            </script>
            @endscript
        @endif
    </div>
</div>