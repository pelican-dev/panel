<x-filament-panels::page class="fi-console-page">
    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="$this->getWidgetData()"
        :widgets="$this->getVisibleWidgets()"
    />

    <x-filament-actions::modals />

</x-filament-panels::page>
