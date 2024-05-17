@props([
    'sidebar',
])

<div class="mt-8">
    <nav class="flex items-center gap-x-2 bg-white px-4 py-2 rounded-md dark:bg-gray-900 dark:ring-white/10 md:px-4 lg:px-4 overflow-x-scroll">
        @if ($sidebar->getTitle() != null || $sidebar->getDescription() != null)
            <div class="me-6 hidden lg:flex flex-col">
                <h3 class="text-base font-medium text-slate-700 dark:text-white truncate block">
                    {{ $sidebar->getTitle() }}
                </h3>
                <p class="text-xs text-gray-400 flex items-center gap-x-1">
                    {{ $sidebar->getDescription() }}
                </p>
            </div>
        @endif

        @if (count($sidebar->getNavigationItems()))
            <ul class="flex items-center gap-x-4">
                @foreach ($sidebar->getNavigationItems() as $group)
                    @if ($groupLabel = $group->getLabel())
                        <x-filament::dropdown placement="bottom-start" teleport>
                            <x-slot name="trigger">
                                <x-filament-panels::topbar.item :active="$group->isActive()" :icon="$group->getIcon()">
                                    {{ $groupLabel }}
                                </x-filament-panels::topbar.item>
                            </x-slot>

                            <x-filament::dropdown.list>
                                @foreach ($group->getItems() as $item)
                                    @php
                                        $icon = $item->getIcon();
                                        $shouldOpenUrlInNewTab = $item->shouldOpenUrlInNewTab();
                                    @endphp

                                    <x-filament::dropdown.list.item
                                            :badge="$item->getBadge()"
                                            :badge-color="$item->getBadgeColor()"
                                            :href="$item->getUrl()"
                                            :icon="$item->isActive() ? ($item->getActiveIcon() ?? $icon) : $icon"
                                            tag="a"
                                            :target="$shouldOpenUrlInNewTab ? '_blank' : null">
                                        {{ $item->getLabel() }}
                                    </x-filament::dropdown.list.item>
                                @endforeach
                            </x-filament::dropdown.list>
                        </x-filament::dropdown>
                    @else
                        @foreach ($group->getItems() as $item)
                            <x-filament-page-with-sidebar::topbar.item
                                    :active="$item->isActive()"
                                    :active-icon="$item->getActiveIcon()"
                                    :badge="$item->getBadge()"
                                    :badge-color="$item->getBadgeColor()"
                                    :icon="$item->getIcon()"
                                    :should-open-url-in-new-tab="$item->shouldOpenUrlInNewTab()"
                                    :url="$item->getUrl()">
                                {{ $item->getLabel() }}
                            </x-filament-page-with-sidebar::topbar.item>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        @endif
    </nav>
</div>