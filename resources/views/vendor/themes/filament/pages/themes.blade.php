<x-filament-panels::page>
    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('themes::themes.primary_color') }}
                </h3>

                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    {{ __('themes::themes.select_base_color') }}
                </p>
            </div>
        </header>

        <div class="flex items-center gap-4 border-t py-6">
            @if ($this->getCurrentTheme() instanceof \Hasnayeen\Themes\Contracts\HasChangeableColor)
                @foreach ($this->getColors() as $name => $color)
                    <button
                        wire:click="setColor('{{ $name }}')"
                        @class([
                            'w-4 h-4 rounded-full',
                            'ring p-1 border' => $this->getColor() === $name,
                        ])
                        style="background-color: rgb({{ $color[500] }});">
                    </button>
                @endforeach
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <input type="color" id="custom" name="custom" class="w-4 h-4" wire:change="setColor($event.target.value)" value="" />
                    <label for="custom">{{ __('themes::themes.custom') }}</label>
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-400">{{ __('themes::themes.no_changing_primary_color') }}</p>
            @endif
        </div>
    </section>

    <section class="">
        <header class="flex items-center gap-x-3 overflow-hidden py-4">
            <div class="grid flex-1 gap-y-1">
                <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('themes::themes.themes') }}
                </h3>
        
                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    {{ __('themes::themes.select_interface') }}
                </p>
            </div>
        </header>

        <div class="grid grid-cols-1 gap-6 border-t py-6">
            @foreach ($this->getThemes() as $name => $theme)
                @php
                    $noLightMode = in_array(\Hasnayeen\Themes\Contracts\HasOnlyDarkMode::class, class_implements($theme));
                    $noDarkMode = in_array(\Hasnayeen\Themes\Contracts\HasOnlyLightMode::class, class_implements($theme));
                    $supportColorChange = in_array(\Hasnayeen\Themes\Contracts\HasChangeableColor::class, class_implements($theme));
                @endphp

                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center space-x-4">
                            <div>{{ \Illuminate\Support\Str::title($name) }}</div>
                            @if ($supportColorChange)
                                <span
                                    x-data="{}"
                                    x-tooltip="{
                                        content: '{{ __('themes::themes.support_changing_primary_color') }}',
                                        theme: $store.theme,
                                    }"
                                    class="bg-primary-200 flex items-center justify-center p-1 rounded-full">
                                    <svg class="w-4 h-4 dark:text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paintbrush-2">
                                        <path d="M14 19.9V16h3a2 2 0 0 0 2-2v-2H5v2c0 1.1.9 2 2 2h3v3.9a2 2 0 1 0 4 0Z" />
                                        <path d="M6 12V2h12v10" />
                                        <path d="M14 2v4" />
                                        <path d="M10 2v2" />
                                    </svg>
                                </span>
                            @endif
                            @if (! $noLightMode)
                                <span
                                    x-data="{}"
                                    x-tooltip="{
                                        content: '{{ __('themes::themes.support_light_mode') }}',
                                        theme: $store.theme,
                                    }"
                                    class="bg-primary-200 flex items-center justify-center p-1 rounded-full">
                                    <svg class="w-4 h-4 dark:text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sun">
                                        <circle cx="12" cy="12" r="4" />
                                        <path d="M12 2v2" />
                                        <path d="M12 20v2" />
                                        <path d="m4.93 4.93 1.41 1.41" />
                                        <path d="m17.66 17.66 1.41 1.41" />
                                        <path d="M2 12h2" />
                                        <path d="M20 12h2" />
                                        <path d="m6.34 17.66-1.41 1.41" />
                                        <path d="m19.07 4.93-1.41 1.41" />
                                    </svg>
                                </span>
                            @endif
                            @if (! $noDarkMode)
                                <span
                                    x-data="{}"
                                    x-tooltip="{
                                        content: '{{ __('themes::themes.support_dark_mode') }}',
                                        theme: $store.theme,
                                    }"
                                    class="bg-primary-200 flex items-center justify-center p-1 rounded-full">
                                    <svg class="w-4 h-4 dark:text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-moon">
                                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                                    </svg>
                                </span>
                            @endif
                            @if ($this->getCurrentTheme()->getName() === $name)
                                <span
                                    x-data="{}"
                                    x-tooltip="{
                                        content: '{{ __('themes::themes.theme_active') }}',
                                        theme: $store.theme,
                                    }"
                                    class="bg-primary-200 flex items-center justify-center p-1 rounded-full">
                                    <svg class="w-4 h-4 dark:text-gray-800" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle-2">
                                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
                                        <path d="m9 12 2 2 4-4" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </x-slot>
                    
                    <x-slot name="headerEnd">
                        <x-filament::button wire:click="setTheme('{{ $name }}')" size="xs" outlined>
                            {{ __('themes::themes.select') }}
                        </x-filament::button>
                    </x-slot>

                    @php
                        $noLightMode = in_array(\Hasnayeen\Themes\Contracts\HasOnlyDarkMode::class, class_implements($theme));
                        $noDarkMode = in_array(\Hasnayeen\Themes\Contracts\HasOnlyLightMode::class, class_implements($theme));
                    @endphp
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            @if ($noLightMode)
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.no_light_mode') }}</h3>
                            @else
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.light') }}</h3>
                                <img src="{{ url('https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/'.$name.'-light.png') }}" alt="{{ $name }} theme preview (light version)" class="border dark:border-gray-700 rounded-lg">
                            @endif
                        </div>
        
                        <div>
                            @if ($noDarkMode)
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.no_dark_mode') }}</h3>
                            @else
                                <h3 class="text-sm font-semibold text-gray-600 pb-4">{{ __('themes::themes.dark') }}</h3>
                                <img src="{{ url('https://raw.githubusercontent.com/Hasnayeen/themes/3.x/assets/'.$name.'-dark.png') }}" alt="{{ $name }} theme preview (dark version)" class="border dark:border-gray-700 rounded-lg">
                            @endif
                        </div>
                    </div>
                </x-filament::section>
            @endforeach
        </div>
    </section>
</x-filament-panels::page>
