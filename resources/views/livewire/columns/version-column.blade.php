@php
    use Filament\Tables\Columns\IconColumn\IconColumnSize;

    $node = $getState();
    $size = $getSize($state) ?? IconColumnSize::Large;

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
    @livewire('node-system-information', ['node' => $node, 'lazy' => true, 'sizeClasses' => $sizeClasses])
</div>
