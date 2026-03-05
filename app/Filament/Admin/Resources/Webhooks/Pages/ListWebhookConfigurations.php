<?php

namespace App\Filament\Admin\Resources\Webhooks\Pages;

use App\Enums\WebhookScope;
use App\Filament\Admin\Resources\Webhooks\WebhookResource;
use App\Models\WebhookConfiguration;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListWebhookConfigurations extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = WebhookResource::class;

    /** @return array<Action> */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->hidden(fn () => $this->activeTab === 'server-webhooks'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'global-webhooks' => Tab::make('Global Webhooks')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('scope', WebhookScope::GLOBAL))
                ->badge(WebhookConfiguration::where('scope', WebhookScope::GLOBAL)->count()),
            'server-webhooks' => Tab::make('Server Webhooks')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('scope', WebhookScope::SERVER)->with('server'))
                ->badge(WebhookConfiguration::where('scope', WebhookScope::SERVER)->count()),
        ];
    }
}
