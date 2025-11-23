@php
    $currentValue = $getState();
    $maxValue = $getMaxValue();
    $status = $getProgressStatus();
    $percentage = $getProgressPercentage();
    $label = $getProgressLabel();
    $color = $getProgressColor();

    if (is_array($color)) {
        $color = $color[0] ?? 'gray';
    }

    $isVar = str_starts_with($color, 'var(');
    $isRgb = str_starts_with($color, 'rgb');

    if ($isRgb) {
        $lightBackgroundColor = str_replace('rgb(', 'rgba(', rtrim($color, ')') . ', 0.15)');
    } elseif ($isVar) {
        $lightBackgroundColor = "color-mix(in srgb, {$color} 15%, transparent)";
    } else {
        $lightBackgroundColor = "color-mix(in srgb, {$color} 15%, transparent)";
    }

    $isDanger = $status === 'danger';

    $lighterColor = $color;
    $animClass = null;

    if ($isDanger) {
        $lighterColor = "color-mix(in srgb, {$color} 50%, #ffffff)";
        $animClass = 'danger-pulse-' . substr(md5($color), 0, 8);
    }
@endphp

<div
    {{
        $attributes
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-ta-text block w-full px-3'])
    }}
>
    @if($isDanger && $animClass)
        <style>
            @keyframes {{ $animClass }}           {
                0% {
                    color: {{ $color }};
                }
                50% {
                    color: {{ $lighterColor }};
                }
                100% {
                    color: {{ $color }};
                }
            }

            .{{ $animClass }}           {
                animation: {{ $animClass }} 1s ease-in-out infinite;
            }
        </style>
    @endif

    <div @class(['flex flex-col gap-2'])>
        <div
            @class(['relative rounded-full overflow-hidden w-full'])
            style="height: 0.725rem; background-color: {{ $lightBackgroundColor }};"
            role="progressbar"
            aria-valuenow="{{ $currentValue }}"
            aria-valuemin="0"
            aria-valuemax="{{ $maxValue ?? 100 }}"
            aria-label="{{ $label }}"
        >
            <div
                @class(['h-full rounded-full transition-all duration-300 ease-in-out'])
                style="width: {{ $percentage }}%; background-color: {{ $color }};"
            ></div>
        </div>
        <span
            @class([
                'text-sm text-center',
                'text-gray-500 dark:text-gray-400' => ! $isDanger,
                'font-bold' => $isDanger,
                $animClass => $isDanger && $animClass,
            ])
            @if($isDanger)
                role="status"
            aria-live="assertive"
            style="color: {{ $color }};"
            @else
                style="color: unset;"
            @endif
        >
            {{ $label }}
        </span>
    </div>
</div>

