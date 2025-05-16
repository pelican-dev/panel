<x-filament-widgets::widget>
    <x-filament::section
        icon="tabler-heart-filled"
        icon-color="danger"
        id="intro-support"
        collapsible
        persist-collapsed
        :header-actions="$actions"
    >
        <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-support.heading') }}</x-slot>

        <p>{{ trans('admin/dashboard.sections.intro-support.content') }}</p>

        <p><br /></p>

        <p>{{ trans('admin/dashboard.sections.intro-support.extra_note') }}</p>
    </x-filament::section>
</x-filament-widgets::widget>
