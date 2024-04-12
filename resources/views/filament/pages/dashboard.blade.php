<x-filament-panels::page>

    <x-filament::tabs label="Content tabs">
        <x-filament::tabs.item disabled>Panel's Resources: </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-server-2"
            :active="$activeTab === 'nodes'"
            wire:click="$set('activeTab', 'nodes')"
        >
            Nodes
            <x-slot name="badge">{{ $nodesCount }}</x-slot>
        </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-brand-docker"
            :active="$activeTab === 'servers'"
            wire:click="$set('activeTab', 'servers')"
        >
            Servers
            <x-slot name="badge">{{ $serversCount }}</x-slot>
        </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-eggs"
            :active="$activeTab === 'eggs'"
            wire:click="$set('activeTab', 'eggs')"
        >
            Eggs
            <x-slot name="badge">{{ $eggsCount }}</x-slot>
        </x-filament::tabs.item>

        <x-filament::tabs.item
            icon="tabler-users"
            :active="$activeTab === 'users'"
            wire:click="$set('activeTab', 'users')"
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
        <x-filament::section icon="tabler-code" icon-color="primary" id="intro-developers" collapsible persist-collapsed collapsed>
            <x-slot name="heading">Information for Developers</x-slot>

            <p>Thank you for trying out the development version!</p>

            <p>
                <br />
                If you run into any issues, please report them on GitHub.
                <br />
                <br />
            </p>

            <p>
                <x-filament::button
                    style="margin-top: 5px;"
                    icon="tabler-brand-github"
                    color="gray"
                    tag="a"
                    href="https://github.com/pelican-dev/panel/issues/new/choose"
                >
                    Report Issue on GitHub
                </x-filament::button>
            </p>

        </x-filament::section>
    @endif

    {{-- No Nodes Created --}}
    @if ($nodesCount <= 0)
        <x-filament::section icon="tabler-server-2" icon-color="primary" id="intro-first-node" collapsible persist-collapsed>
            <x-slot name="heading">Create First New Node</x-slot>

            <p>It looks like you don't have any Nodes set up yet, but don't worry because you can follow along below:</p>

            <p>
                <br />
                If you run into any issues, please report them on GitHub.
                <br />
                <br />
            </p>

            <p>
                <x-filament::button
                    style="margin-top: 5px;"
                    icon="tabler-server-2"
                    color="primary"
                    tag="a"
                    :href="route('filament.admin.resources.nodes.create')"
                >
                    Create Node in Pelican
                </x-filament::button>
            </p>

        </x-filament::section>
    @endif

    {{-- No Nodes Active --}}


    <x-filament::section icon="tabler-cash" icon-color="success" id="intro-support" collapsible persist-collapsed>
        <x-slot name="heading">Support Pelican</x-slot>

        <p>Thank you for using Pelican, this could only be achieved through the support of you, our contributors, and the rest of our supporters!</p>

        <p><br /></p>

        <p>We appreciate any and all support from anybody.</p>

        <p><br /></p>

        <x-filament::button
            style="margin-top: 5px;"
            color="success"
            icon="tabler-pig-money"
            tag="a"
            href="https://pelican.dev/donate"
        >
            Donate Directly
        </x-filament::button>

    </x-filament::section>



    <x-filament::section icon="tabler-question-mark" icon-color="info" id="intro-help" collapsible persist-collapsed>
        <x-slot name="heading">Need Help?</x-slot>

        <p>Check out the documentation first! If you still need assistance then, fly onto our Discord server!</p>

        <p><br /></p>

        <x-filament::button
            color="primary"
            icon="tabler-speedboat"
            tag="a"
            href="https://pelican.dev/docs"
        >
            Read Documentation
        </x-filament::button>

        <x-filament::button
            color="info"
            icon="tabler-brand-discord"
            tag="a"
            href="https://discord.gg/pelican-panel"
        >
            Get Help in Discord
        </x-filament::button>

    </x-filament::section>

    <div>
    </div>
</x-filament-panels::page>
