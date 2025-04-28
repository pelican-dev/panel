@php
    use Filament\Support\Enums\IconSize;

    $node = $getRecord();
    $size = $getSize($node) ?? IconSize::Large;

    $sizeClasses = match ($size) {
        IconSize::ExtraSmall, 'xs' => 'fi-ta-icon-item-size-xs h-3 w-3',
        IconSize::Small, 'sm' => 'fi-ta-icon-item-size-sm h-4 w-4',
        IconSize::Medium, 'md' => 'fi-ta-icon-item-size-md h-5 w-5',
        IconSize::Large, 'lg' => 'fi-ta-icon-item-size-lg h-6 w-6',
        IconSize::ExtraLarge, 'xl' => 'fi-ta-icon-item-size-xl h-7 w-7',
        IconSize::TwoExtraLarge, '2xl' => 'fi-ta-icon-item-size-2xl h-8 w-8',
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
    @livewire('node-system-information', ['node' => $node, 'lazy' => true, 'sizeClasses' => $sizeClasses])
</div>
