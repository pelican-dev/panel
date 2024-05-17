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
            <x-slot name="heading">{{ trans('dashboard/index.sections.intro-developers.heading') }}</x-slot>

            <p>This side is still in development and bugs or issues may occur</p>

            <p><br /></p>

            <p>{{  trans('dashboard/index.sections.intro-developers.extra_note') }}</p>

        </x-filament::section>
    @endif
    
</x-filament-panels::page>