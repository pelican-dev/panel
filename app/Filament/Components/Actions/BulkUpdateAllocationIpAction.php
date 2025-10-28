<?php

namespace App\Filament\Components\Actions;

use App\Models\Allocation;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class BulkUpdateAllocationIpAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'bulk_update_ip';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('admin/node.bulk_update_ip'));

        $this->icon('tabler-replace');

        $this->color('warning');

        $this->requiresConfirmation();

        $this->modalHeading(trans('admin/node.bulk_update_ip'));

        $this->modalDescription(trans('admin/node.bulk_update_ip_description'));

        $this->modalIconColor('warning');

        $this->modalSubmitActionLabel(trans('admin/node.update_ip'));

        $this->schema(function (Collection $records) {
            // Get unique IPs from selected allocations
            $currentIps = $records->pluck('ip')->unique()->values()->all();
            
            // Get available IPs from the node (we need access to the owner record)
            // This will be set dynamically when the action is used
            $availableIps = $this->getAvailableIps();

            return [
                Select::make('old_ip')
                    ->label(trans('admin/node.old_ip'))
                    ->options(array_combine($currentIps, $currentIps))
                    ->required()
                    ->helperText(trans('admin/node.old_ip_help'))
                    ->live(),
                Select::make('new_ip')
                    ->label(trans('admin/node.new_ip'))
                    ->options(fn () => array_combine($availableIps, $availableIps))
                    ->required()
                    ->helperText(trans('admin/node.new_ip_help'))
                    ->different('old_ip'),
            ];
        });

        $this->action(function (Collection $records, array $data) {
            $oldIp = $data['old_ip'];
            $newIp = $data['new_ip'];

            // Filter records to only those with the old IP
            $recordsToUpdate = $records->filter(fn (Allocation $allocation) => $allocation->ip === $oldIp);

            if ($recordsToUpdate->count() === 0) {
                Notification::make()
                    ->title(trans('admin/node.no_allocations_to_update'))
                    ->warning()
                    ->send();

                return;
            }

            $updated = 0;
            $failed = 0;

            /** @var Allocation $allocation */
            foreach ($recordsToUpdate as $allocation) {
                try {
                    $allocation->update(['ip' => $newIp]);
                    $updated++;
                } catch (\Exception $exception) {
                    $failed++;
                    report($exception);
                }
            }

            Notification::make()
                ->title(trans('admin/node.ip_updated', ['count' => $updated, 'total' => $recordsToUpdate->count()]))
                ->body($failed > 0 ? trans('admin/node.ip_update_failed', ['count' => $failed]) : null)
                ->status($failed > 0 ? 'warning' : 'success')
                ->persistent()
                ->send();
        });

        $this->deselectRecordsAfterCompletion();
    }

    protected array $availableIps = [];

    public function availableIps(array $ips): static
    {
        $this->availableIps = $ips;

        return $this;
    }

    protected function getAvailableIps(): array
    {
        return $this->availableIps;
    }
}
