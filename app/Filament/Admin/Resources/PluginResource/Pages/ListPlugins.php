<?php

namespace App\Filament\Admin\Resources\PluginResource\Pages;

use App\Filament\Admin\Resources\PluginResource;
use App\Services\Helpers\PluginService;
use Filament\Resources\Pages\ListRecords;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    public function reorderTable(array $order): void
    {
        app(PluginService::class)->updateLoadOrder($order); // @phpstan-ignore-line
    }
}
