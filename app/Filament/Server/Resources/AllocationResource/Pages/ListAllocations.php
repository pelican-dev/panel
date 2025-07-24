<?php

namespace App\Filament\Server\Resources\AllocationResource\Pages;

use App\Facades\Activity;
use App\Filament\Server\Resources\AllocationResource;
use App\Models\Permission;
use App\Models\Server;
use App\Services\Allocations\FindAssignableAllocationService;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconSize;

class ListAllocations extends ListRecords
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = AllocationResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return [
            Action::make('addAllocation')
                ->hiddenLabel()->iconButton()->iconSize(IconSize::Large)
                ->icon(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'tabler-network-off' : 'tabler-network')
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, $server))
                ->tooltip(fn () => $server->allocations()->count() >= $server->allocation_limit ? trans('server/network.limit') : trans('server/network.add'))
                ->hidden(fn () => !config('panel.client_features.allocations.enabled'))
                ->disabled(fn () => $server->allocations()->count() >= $server->allocation_limit)
                ->color(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'danger' : 'primary')
                ->action(function (FindAssignableAllocationService $service) use ($server) {
                    $allocation = $service->handle($server);

                    if (!$server->allocation_id) {
                        $server->update(['allocation_id' => $allocation->id]);
                    }

                    Activity::event('server:allocation.create')
                        ->subject($allocation)
                        ->property('allocation', $allocation->address)
                        ->log();
                }),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getTitle(): string
    {
        return trans('server/network.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('server/network.title');
    }
}
