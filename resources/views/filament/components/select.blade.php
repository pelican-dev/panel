@php
    use Filament\Support\Facades\FilamentView;

    $canSelectPlaceholder = $canSelectPlaceholder();
    $isDisabled = $isDisabled();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :inline-label-vertical-alignment="\Filament\Support\Enums\VerticalAlignment::Center"
>
    <x-filament.wrapper
        :disabled="$isDisabled"
        :inline-prefix="$isPrefixInline"
        :inline-suffix="$isSuffixInline"
        :prefix="$prefixLabel"
        :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon"
        :prefix-icon-color="$getPrefixIconColor()"
        :suffix="$suffixLabel"
        :suffix-actions="$suffixActions"
        :suffix-icon="$suffixIcon"
        :suffix-icon-color="$getSuffixIconColor()"
        :valid="! $errors->has($statePath)"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-select'])
        "
    >
        <x-filament::input.select
                :autofocus="$isAutofocused()"
                :disabled="$isDisabled"
                :id="$getId()"
                :inline-prefix="$isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel))"
                :inline-suffix="$isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel))"
                :required="$isRequired() && (! $isConcealed())"
                :attributes="
                    $getExtraInputAttributeBag()
                        ->merge([
                            $applyStateBindingModifiers('wire:model') => $statePath,
                        ], escape: false)
                "
            >
                @php
                    $isHtmlAllowed = $isHtmlAllowed();
                @endphp

                @if ($canSelectPlaceholder)
                    <option value="">
                        @if (! $isDisabled)
                            {{ $getPlaceholder() }}
                        @endif
                    </option>
                @endif

                @foreach ($getOptions() as $value => $label)
                    @if (is_array($label))
                        <optgroup label="{{ $value }}">
                            @foreach ($label as $groupedValue => $groupedLabel)
                                <option
                                    @disabled($isOptionDisabled($groupedValue, $groupedLabel))
                                    value="{{ $groupedValue }}"
                                >
                                    @if ($isHtmlAllowed)
                                        {!! $groupedLabel !!}
                                    @else
                                        {{ $groupedLabel }}
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                    @else
                        <option
                            @disabled($isOptionDisabled($value, $label))
                            value="{{ $value }}"
                        >
                            @if ($isHtmlAllowed)
                                {!! $label !!}
                            @else
                                {{ $label }}
                            @endif
                        </option>
                    @endif
                @endforeach
            </x-filament::input.select>

    </x-filament.wrapper>
</x-dynamic-component>
