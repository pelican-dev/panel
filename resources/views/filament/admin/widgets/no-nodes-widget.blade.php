<x-filament-widgets::widget>
    <x-filament::section
        icon="tabler-server-2"
        icon-color="primary"
        id="intro-first-node"
        collapsible
        persist-collapsed
        :header-actions="$actions"
    >
        <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-first-node.heading') }}</x-slot>

        <p>{{ trans('admin/dashboard.sections.intro-first-node.content') }}</p>
    </x-filament::section>
</x-filament-widgets::widget>
