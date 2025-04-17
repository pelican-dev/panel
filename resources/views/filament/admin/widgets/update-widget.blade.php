<x-filament-widgets::widget>
    @if (!$isLatest)
        <x-filament::section
            icon="tabler-info-circle"
            icon-color="warning"
            id="intro-update-available"
            :header-actions="$actions"
        >
            <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-update-available.heading') }}</x-slot>

            <p>{{ trans('admin/dashboard.sections.intro-update-available.content', ['latestVersion' => $latestVersion]) }}</p>

        </x-filament::section>
    @else
        <x-filament::section
            icon="tabler-checkbox"
            icon-color="success"
            id="intro-no-update"
        >
            <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-no-update.heading') }}</x-slot>

            <p>{{ trans('admin/dashboard.sections.intro-no-update.content', ['version' => $version]) }}</p>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
