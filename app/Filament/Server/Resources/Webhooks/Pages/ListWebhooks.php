<?php

namespace App\Filament\Server\Resources\Webhooks\Pages;

use App\Filament\Server\Resources\Webhooks\WebhookResource;
use App\Models\Server;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;

class ListWebhooks extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = WebhookResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('tabler-plus')
                ->hiddenLabel()
                ->iconButton()
                ->iconSize(IconSize::ExtraLarge)
                ->hidden(function () {
                    $server = Filament::getTenant();

                    return !$server instanceof Server || $server->webhookConfigurations()->count() <= 0;
                }),
        ];
    }
}
