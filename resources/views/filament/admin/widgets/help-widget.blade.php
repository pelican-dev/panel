<x-filament-widgets::widget>
    <x-filament::section
        icon="tabler-question-mark"
        icon-color="info"
        id="intro-help"
        collapsible
        persist-collapsed
        :after-header="$action"
    >
        <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-help.heading') }}</x-slot>
        
        <p>{{ trans('admin/dashboard.sections.intro-help.content') }}</p>
    </x-filament::section>
</x-filament-widgets::widget>
