<?php

namespace App\Filament\Admin\Resources\Plugins\Pages;

use App\Enums\PluginCategory;
use App\Facades\Plugins;
use App\Filament\Admin\Resources\PluginResource;
use App\Models\Plugin;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    public function reorderTable(array $order, int|string|null $draggedRecordKey = null): void
    {
        Plugins::updateLoadOrder($order);
    }

    public function getTabs(): array
    {
        $tabs = [];

        foreach (PluginCategory::cases() as $category) {
            $tabs[$category->value] = Tab::make($category->value)
                ->label($category->getLabel())
                ->icon($category->getIcon())
                ->badge(Plugin::whereCategory($category->value)->count())
                ->modifyQueryUsing(fn ($query) => $query->whereCategory($category->value));
        }

        $tabs['all'] = Tab::make('all')
            ->label(trans('admin/plugin.all'))
            ->badge(Plugin::count());

        return $tabs;
    }
}
