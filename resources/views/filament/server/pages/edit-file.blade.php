<x-filament-panels::page class="fi-resource-edit-file-page">
    <x-filament-panels::form
        id="form"
        :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()"
        wire:submit="save"
    >
        {{ $this->form }}
    </x-filament-panels::form>
</x-filament-panels::page>
