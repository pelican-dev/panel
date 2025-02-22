@php
    use Filament\Support\Enums\IconPosition;
    use Filament\Support\Facades\FilamentView;
    $tag = 'div';
@endphp

<{!! $tag !!}
{{
    $getExtraAttributeBag()
        ->class([
            'fi-wi-stats-overview-stat relative rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10',
        ])
}}
>
<div class="grid grid-flow-row">
    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
        {{ $getLabel() }}
    </span>
    <div class="text-xl font-semibold text-gray-950 dark:text-white">
        {{ $getValue() }}
    </div>

    @if ($description = $getDescription())
        <div class="flex items-center">
            <span>
                {{ $description }}
            </span>
        </div>
    @endif
</div>
</{!! $tag !!}>
