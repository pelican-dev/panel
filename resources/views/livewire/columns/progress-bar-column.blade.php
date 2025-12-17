@php
    $currentValue = $getState();
    $maxValue = $getMaxValue();
    $status = $getProgressStatus();
    $percentage = $getProgressPercentage();
    $label = $getProgressLabel();
    $color = $getProgressColor();
    $resolved = \App\Filament\Components\Tables\Columns\ProgressBarColumn::resolveColor($color);
    $color = $resolved ?? (is_string($color) ? $color : 'gray');
    $colorStr = is_string($color) ? $color : 'gray';
    $isRgb = str_starts_with($colorStr, 'rgb(');

    if ($isRgb) {
        $lightBackgroundColor = str_replace('rgb(', 'rgba(', rtrim($colorStr, ')') . ', 0.15)');
    } else {
        $lightBackgroundColor = "color-mix(in srgb, {$colorStr} 15%, transparent)";
    }

    $isDanger = $status === 'danger';

    $lighterColor = $colorStr;
    $animClass = null;

    if ($isDanger) {
        $lighterColor = "color-mix(in srgb, {$colorStr} 50%, #ffffff)";
        $animClass = 'danger-pulse-' . substr(md5($colorStr), 0, 8);
    }
@endphp

<div
    @class(['fi-ta-text block w-full px-3'])
>
    @if($isDanger && $animClass)
        <style>
            @keyframes {{ $animClass }}                               {
                0% {
                    color: {{ $colorStr }};
                }
                50% {
                    color: {{ $lighterColor }};
                }
                100% {
                    color: {{ $colorStr }};
                }
            }

            .{{ $animClass }}                               {
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
                style="width: {{ $percentage }}%; background-color: {{ $colorStr }};"
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
            style="color: {{ $colorStr }};"
            @else
                style="color: unset;"
            @endif
        >
            {{ $label }}
        </span>
    </div>
</div>
