<?php

namespace App\Filament\Admin\Resources\PluginResource\Pages;

use App\Facades\Plugins;
use App\Filament\Admin\Resources\PluginResource;
use Filament\Resources\Pages\ListRecords;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    public function reorderTable(array $order): void
    {
        Plugins::updateLoadOrder($order);
    }
}
