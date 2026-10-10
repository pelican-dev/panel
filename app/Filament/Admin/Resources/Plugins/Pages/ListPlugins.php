<?php

namespace App\Filament\Admin\Resources\Plugins\Pages;

use App\Enums\PluginCategory;
use App\Filament\Admin\Resources\Plugins\PluginResource;
use App\Models\Plugin;
use App\Services\Helpers\PluginService;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;

class ListPlugins extends ListRecords
{
    protected static string $resource = PluginResource::class;

    public function reorderTable(array $order, int|string|null $draggedRecordKey = null): void
    {
        abort_unless($this->getTable()->isReorderable(), 403);

        /** @var PluginService $pluginService */
        $pluginService = app(PluginService::class); // @phpstan-ignore myCustomRules.forbiddenGlobalFunctions

        $pluginService->updateLoadOrder($order);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Callout::make(trans('admin/plugin.safe_mode_active'))
                    ->warning()
                    ->visible(fn (PluginService $pluginService) => $pluginService->isSafeModeActive()),
                $this->getTabsContentComponent(),
                RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE),
                EmbeddedTable::make(),
                RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER),
            ]);
    }

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('all')
                ->label(trans('admin/plugin.all'))
                ->badge(Plugin::count()),
        ];

        foreach (PluginCategory::cases() as $category) {
            $query = Plugin::whereCategory($category->value);
            $tabs[$category->value] = Tab::make($category->value)
                ->label($category->getLabel())
                ->icon($category->getIcon())
                ->badge($query->count())
                ->modifyQueryUsing(fn () => $query);
        }

        return $tabs;
    }
}
