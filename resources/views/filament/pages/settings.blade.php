<x-filament-panels::page
    @class([
        'fi-page-settings'
    ])
    id="form"
    :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
    wire:submit="save"
>
    {{ $this->form }}
</x-filament-panels::page>
