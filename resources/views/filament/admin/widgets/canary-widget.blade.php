<x-filament-widgets::widget>
    <x-filament::section
        icon="tabler-code"
        icon-color="primary"
        id="intro-developers"
        collapsible
        persist-collapsed
        collapsed
        :header-actions="$actions"
    >
        <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-developers.heading') }}</x-slot>

        <p>{{ trans('admin/dashboard.sections.intro-developers.content') }}</p>

        <p><br /></p>

        <p>{{ trans('admin/dashboard.sections.intro-developers.extra_note') }}</p>
    </x-filament::section>
</x-filament-widgets::widget>
