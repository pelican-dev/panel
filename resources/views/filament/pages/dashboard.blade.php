<x-filament-panels::page>
    <x-filament-panels::header
        :actions="$this->getCachedHeaderActions()"
        :breadcrumbs="filament()->hasBreadcrumbs() ? $this->getBreadcrumbs() : []"
        :heading=" trans('admin/dashboard.heading')"
        :subheading="trans('admin/dashboard.version', ['version' => $version])"
    ></x-filament-panels::header>

    <p>{{ trans('admin/dashboard.expand_sections') }}</p>

    @if (!$isLatest)
        <x-filament::section
            icon="tabler-info-circle"
            icon-color="warning"
            id="intro-update-available"
            :header-actions="$updateActions"
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


    @if ($inDevelopment)
        <x-filament::section
            icon="tabler-code"
            icon-color="primary"
            id="intro-developers"
            collapsible
            persist-collapsed
            collapsed
            :header-actions="$devActions"
        >
            <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-developers.heading') }}</x-slot>

            <p>{{ trans('admin/dashboard.sections.intro-developers.content') }}</p>

            <p><br /></p>

            <p>{{ trans('admin/dashboard.sections.intro-developers.extra_note') }}</p>

        </x-filament::section>
    @endif

    {{-- No Nodes Created --}}
    @if ($nodesCount <= 0)
        <x-filament::section
            icon="tabler-server-2"
            icon-color="primary"
            id="intro-first-node"
            collapsible
            persist-collapsed
            :header-actions="$nodeActions"
        >
            <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-first-node.heading') }}</x-slot>

            <p>{{ trans('admin/dashboard.sections.intro-first-node.content') }}</p>

        </x-filament::section>
    @endif

    {{-- No Nodes Active --}}

    <x-filament::section
        icon="tabler-heart-filled"
        icon-color="danger"
        id="intro-support"
        collapsible
        persist-collapsed
        :header-actions="$supportActions"
    >
        <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-support.heading') }}</x-slot>

        <p>{{ trans('admin/dashboard.sections.intro-support.content') }}</p>

        <p><br /></p>

        <p>{{ trans('admin/dashboard.sections.intro-support.extra_note') }}</p>

    </x-filament::section>

    <x-filament::section
        icon="tabler-question-mark"
        icon-color="info"
        id="intro-help"
        collapsible
        persist-collapsed
        :header-actions="$helpActions"
    >
        <x-slot name="heading">{{ trans('admin/dashboard.sections.intro-help.heading') }}</x-slot>
        <p>{{ trans('admin/dashboard.sections.intro-help.content') }}</p>
    </x-filament::section>
</x-filament-panels::page>
