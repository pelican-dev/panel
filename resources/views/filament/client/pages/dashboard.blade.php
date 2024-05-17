<x-filament-panels::page>

    <x-filament-panels::header
        :heading=" $heading"
        :subheading=" $subheading"
    ></x-filament-panels::header>

    <x-filament::tabs disabled>
        <x-filament::tabs.item disabled>{{ trans('dashboard/index.overview') }} </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-server"
        >
            Your Servers
            <x-slot name="badge">{{ $ServersCount}}</x-slot>
        </x-filament::tabs.item>
    </x-filament::tabs>
    
</x-filament-panels::page>