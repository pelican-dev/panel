<x-filament-panels::page>

    <x-filament::tabs disabled>
        <x-filament::tabs.item disabled>Overview: </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-server-2"
        >
            Nodes
            <x-slot name="badge">{{ $nodesCount }}</x-slot>
        </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-brand-docker"
        >
            Servers
            <x-slot name="badge">{{ $serversCount }}</x-slot>
        </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-eggs"
        >
            Eggs
            <x-slot name="badge">{{ $eggsCount }}</x-slot>
        </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-users"
        >
            Users
            <x-slot name="badge">{{ $usersCount }}</x-slot>
        </x-filament::tabs.item>
    </x-filament::tabs>

    <x-filament-panels::header
        :actions="$this->getCachedHeaderActions()"
        :breadcrumbs="filament()->hasBreadcrumbs() ? $this->getBreadcrumbs() : []"
        :heading="'Welcome to Pelican!'"
        :subheading="'Version: ' . config('app.version')"
    ></x-filament-panels::header>

    <p>You can expand the following sections:</p>

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
            <x-slot name="heading">Information for Developers</x-slot>

            <p>Thank you for trying out the development version!</p>

            <p><br /></p>

            <p>If you run into any issues, please report them on GitHub.</p>

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
            <x-slot name="heading">No Nodes Detected</x-slot>

            <p>It looks like you don't have any Nodes set up yet, but don't worry because you click the action button to create your first one!</p>

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
        <x-slot name="heading">Support Pelican</x-slot>

        <p>Thank you for using Pelican, this could only be achieved through the support of you, our contributors, and the rest of our supporters!</p>

        <p><br /></p>

        <p>We appreciate any and all support from anybody.</p>

    </x-filament::section>



    <x-filament::section
        icon="tabler-question-mark"
        icon-color="info"
        id="intro-help"
        collapsible
        persist-collapsed
        :header-actions="$helpActions"
    >
        <x-slot name="heading">Need Help?</x-slot>

        <p>Check out the documentation first! If you still need assistance then, fly onto our Discord server!</p>

    </x-filament::section>

    <div>
    </div>
</x-filament-panels::page>
