<x-filament-panels::page>

    <x-filament-panels::header
        :actions="$this->getCachedHeaderActions()"
        :breadcrumbs="filament()->hasBreadcrumbs() ? $this->getBreadcrumbs() : []"
        :heading=" trans('dashboard/index.heading')"
        :subheading="trans('strings.version', ['version' => $version])"
    ></x-filament-panels::header>

    <p>{{ trans('dashboard/index.expand_sections') }}</p>

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
            <x-slot name="heading">{{ trans('dashboard/index.sections.intro-developers.heading') }}</x-slot>

            <p>{{  trans('dashboard/index.sections.intro-developers.content') }}</p>

            <p><br /></p>

            <p>{{  trans('dashboard/index.sections.intro-developers.extra_note') }}</p>

        </x-filament::section>
    @endif

    @if (!$isLatest)
        <x-filament::section
            icon="tabler-info-circle"
            icon-color="warning"
            id="intro-update"
            collapsible
            persist-collapsed
            :header-actions="$updateActions"
        >
            <x-slot name="heading">{{ trans('dashboard/index.sections.intro-update.heading') }}</x-slot>

            <p>{{  trans('dashboard/index.sections.intro-update.content', ['latestVersion' => $latestVersion]) }}</p>

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
            <x-slot name="heading">{{ trans('dashboard/index.sections.intro-first-node.heading') }}</x-slot>

            <p>{{  trans('dashboard/index.sections.intro-first-node.content') }}</p>

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
        <x-slot name="heading">{{ trans('dashboard/index.sections.intro-support.heading') }}</x-slot>

        <p>{{  trans('dashboard/index.sections.intro-support.content') }}</p>

        <p><br /></p>

        <p>{{  trans('dashboard/index.sections.intro-support.extra_note') }}</p>

    </x-filament::section>



    <x-filament::section
        icon="tabler-question-mark"
        icon-color="info"
        id="intro-help"
        collapsible
        persist-collapsed
        :header-actions="$helpActions"
    >
        <x-slot name="heading">{{ trans('dashboard/index.sections.intro-help.heading') }}</x-slot>

        <p>
            Check out the documentation first!
            If you still need assistance then, fly onto our
            <a class="underline" href="https://discord.gg/pelican-panel">Discord server</a>!
        </p>

    </x-filament::section>

    <div>
    </div>
</x-filament-panels::page>
