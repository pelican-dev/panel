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

    @if ($inDevelopment)
        <x-filament::section
            icon="tabler-code"
            icon-color="primary"
            id="intro-developers"
           :header-actions="$development"
        >
            <x-slot name="heading">Information about this client side</x-slot>

            <p>This client side is still in development and not completed yet. We suggest that you continue to use the normal client side untill this side is complete</p>

            <p><br /></p>

            <p>{{  trans('dashboard/index.sections.intro-developers.extra_note') }}</p>

        </x-filament::section>
    @endif
    
</x-filament-panels::page>