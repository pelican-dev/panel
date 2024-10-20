<div>
    @switch($version = $node->systemInformation()['version'] ?? 'false')
        @case('false')
            <x-filament::icon
                :icon="'tabler-heart-off'"
                x-tooltip="{ content: 'No Connection', theme: $store.theme }"
                @class(['fi-ta-icon-item', $sizeClasses, 'fi-color-custom text-custom-500 dark:text-custom-400', 'fi-color-danger'])
                @style([\Filament\Support\get_color_css_variables('danger', shades: [400, 500], alias: 'tables::columns.icon-column.item') => true])
            />
            @break
        @default
            <x-filament::icon
                :icon="'tabler-heartbeat'"
                x-tooltip="{ content: 'Version {{ $version ?: 'dev' }}', theme: $store.theme }"
                @class(['fi-ta-icon-item', $sizeClasses, 'fi-color-custom text-custom-500 dark:text-custom-400', 'fi-color-success' => true])
                @style([\Filament\Support\get_color_css_variables('success', shades: [400, 500], alias: 'tables::columns.icon-column.item') => true])
            />
    @endswitch
</div>
