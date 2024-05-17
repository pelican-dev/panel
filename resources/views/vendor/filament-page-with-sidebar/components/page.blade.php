@php
    $sidebar = $this->getSidebar();
    $sidebarWidths = $this->getSidebarWidths();
@endphp

<div>
    @if($sidebar->getPageNavigationLayout() == \AymanAlhattami\FilamentPageWithSidebar\Enums\PageNavigationLayoutEnum::Sidebar)
        <div class="mt-8">
            <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
                <div class="col-[--col-span-default]
                        sm:col-[--col-span-sm]
                        md:col-[--col-span-md]
                        lg:col-[--col-span-lg]
                        xl:col-[--col-span-xl]
                        2xl:col-[--col-span-2xl]
                        rounded"
                     style="--col-span-default: span 12;
                        --col-span-sm: span {{ $sidebarWidths['sm'] ?? 12 }};
                        --col-span-md: span {{ $sidebarWidths['md'] ?? 3 }};
                        --col-span-lg: span {{ $sidebarWidths['lg'] ?? 3 }};
                        --col-span-xl: span {{ $sidebarWidths['xl'] ?? 3 }};
                        --col-span-2xl: span {{ $sidebarWidths['2xl'] ?? 3 }};">
                    <div class="">
                        <div class="flex items-center rtl:space-x-reverse">
                            @if ($sidebar->getTitle() != null || $sidebar->getDescription() != null)
                                <div class="w-full">
                                    @if ($sidebar->getTitle() != null)
                                        <h3 class="text-base font-medium text-slate-700 dark:text-white truncate block">
                                            {{ $sidebar->getTitle() }}
                                        </h3>
                                    @endif

                                    @if ($sidebar->getDescription())
                                        <p class="text-xs text-gray-400 flex items-center gap-x-1">
                                            {{ $sidebar->getDescription() }}

                                            @if ($sidebar->getDescriptionCopyable())
                                                <x-filament::icon
                                                        x-on:click.prevent="
                                            window.navigator.clipboard.writeText('{{ $sidebar->getDescription() }}');
                                            $tooltip('Copied to clipboard', { timeout: 1500 })
                                        "
                                                        icon="heroicon-o-clipboard-document"
                                                        class="h-4 w-4 cursor-pointer hover:text-gray-700 text-gray-400 dark:text-gray-500 dark:hover:text-gray-400" />
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <ul class="@if ($sidebar->getTitle() != null || $sidebar->getDescription() != null) mt-4 @endif space-y-2 font-inter font-medium" wire:ignore>
                            @foreach ($sidebar->getNavigationItems() as $group)
                                <x-filament-page-with-sidebar::group
                                        :collapsible="$group->isCollapsible()"
                                        :icon="$group->getIcon()"
                                        :items="$group->getItems()"
                                        :label="$group->getLabel()"
                                />
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-[--col-span-default]
                        sm:col-[--col-span-sm]
                        md:col-[--col-span-md]
                        lg:col-[--col-span-lg]
                        xl:col-[--col-span-xl]
                        2xl:col-[--col-span-2xl]
                        -mt-8"
                     style="--col-span-default: span 12;
                        --col-span-sm: span {{ 12 - ($sidebarWidths['sm'] ?? 12) }};
                        --col-span-md: span {{ 12 - ($sidebarWidths['md'] ?? 3) }};
                        --col-span-lg: span {{ 12 - ($sidebarWidths['lg'] ?? 3) }};
                        --col-span-xl: span {{ 12 - ($sidebarWidths['xl'] ?? 3) }};
                        --col-span-2xl: span {{ 12 - ($sidebarWidths['2xl'] ?? 3) }}; margin-top: -2em;">
                    {{ $slot }}
                </div>
            </div>
        </div>
    @else
        <x-filament-page-with-sidebar::topbar :sidebar="$sidebar"/>

        {{ $slot }}
   @endif
</div>
