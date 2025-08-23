@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $isRequired = $isRequired();
    $type = $getType();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-slot name="label">
        {{ $getLabel() }}
    </x-slot>

    <x-filament::input.wrapper
        :prefix="$getPrefixLabel()"
        :valid="! $errors->has($statePath)"
        :attributes="\Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())->class([
            'fi-fo-text-input overflow-hidden' => $type === \App\Enums\StartupVariableType::Text,
            'fi-fo-select' => $type === \App\Enums\StartupVariableType::Select
        ])"
    >
        @if ($type === \App\Enums\StartupVariableType::Select)
            <x-filament::input.select
                :id="$getId()"
                :required="$isRequired"
                :disabled="$isDisabled"
                :attributes="
                    $getExtraInputAttributeBag()
                        ->merge([
                            $applyStateBindingModifiers('wire:model') => $statePath,
                        ], escape: false)
                "
            >
                @foreach ($getSelectOptions() as $value)
                    <option value="{{ $value }}">
                        {{ $value }}
                    </option>
                @endforeach
            </x-filament::input.select>
        @else
            <x-filament::input
                :attributes="
                    \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                        ->merge($getExtraAlpineAttributes(), escape: false)
                        ->merge([
                            'id' => $getId(),
                            'required' => $isRequired,
                            'disabled' => $isDisabled,
                            'placeholder' => $getPlaceholder(),
                            $applyStateBindingModifiers('wire:model') => $statePath,
                        ], escape: false)
                "
            />
        @endif
    </x-filament::input.wrapper>
</x-dynamic-component>
