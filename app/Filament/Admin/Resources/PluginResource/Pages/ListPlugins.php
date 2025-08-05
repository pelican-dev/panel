<?php

namespace App\Filament\Admin\Resources\PluginResource\Pages;

use App\Enums\PluginCategory;
use App\Facades\Plugins;
use App\Filament\Admin\Resources\PluginResource;
use App\Models\Plugin;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    public function reorderTable(array $order): void
    {
        Plugins::updateLoadOrder($order);
    }

    public function getTabs(): array
    {
        $tabs = [];

        foreach (PluginCategory::cases() as $category) {
            $tabs[$category->value] = Tab::make($category->getLabel())
                ->icon($category->getIcon())
                ->badge(Plugin::whereCategory($category->value)->count())
                ->modifyQueryUsing(fn ($query) => $query->whereCategory($category->value));
        }

        $tabs['all'] = Tab::make(trans('admin/plugin.all'))
            ->badge(Plugin::count());

        return $tabs;
    }
}
