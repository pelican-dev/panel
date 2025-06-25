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
                ->authorize(fn () => auth()->user()->can(Permission::ACTION_ALLOCATION_CREATE, $server))
                ->label(fn () => $server->allocations()->count() >= $server->allocation_limit ? 'Allocation limit reached' : 'Add Allocation')
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
}
