<?php

namespace App\Filament\Components\Actions;

use App\Models\Allocation;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;

class UpdateNodeAllocations extends Action
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

        $this->schema(function () {
            $currentIps = $this->getOldIps();
            $availableIps = $this->getAvailableIps();

            return [
                Select::make('old_ip')
                    ->label(trans('admin/node.old_ip'))
                    ->options(array_combine($currentIps, $currentIps) ?: [])
                    ->required()
                    ->live(),
                Select::make('new_ip')
                    ->label(trans('admin/node.new_ip'))
                    ->options(fn () => array_combine($availableIps, $availableIps) ?: [])
                    ->required()
                    ->different('old_ip'),
            ];
        });

        $this->action(function (array $data) {
            $oldIp = $data['old_ip'];
            $newIp = $data['new_ip'];
            $nodeId = $this->nodeId;

            $allocations = Allocation::where('node_id', $nodeId)->where('ip', $oldIp)->get();

            if ($allocations->count() === 0) {
                Notification::make()
                    ->title(trans('admin/node.no_allocations_to_update'))
                    ->warning()
                    ->send();

                return;
            }

            $updated = 0;
            $failed = 0;

            foreach ($allocations as $allocation) {
                try {
                    $allocation->update(['ip' => $newIp]);
                    $updated++;
                } catch (Exception $exception) {
                    $failed++;
                    report($exception);
                }
            }

            Notification::make()
                ->title(trans('admin/node.ip_updated', ['count' => $updated, 'total' => $allocations->count()]))
                ->body($failed > 0 ? trans('admin/node.ip_update_failed', ['count' => $failed]) : null)
                ->status($failed > 0 ? 'warning' : 'success')
                ->persistent()
                ->send();
        });
    }

    /** @var string[] */
    protected array $availableIps = [];

    /** @var string[] */
    protected array $oldIps = [];

    protected ?int $nodeId = null;

    /** @param  string[]  $ips */
    public function availableIps(array $ips): static
    {
        $this->availableIps = $ips;

        return $this;
    }

    /** @param  string[]  $ips */
    public function oldIps(array $ips): static
    {
        $this->oldIps = $ips;

        return $this;
    }

    public function forNode(int $nodeId): static
    {
        $this->nodeId = $nodeId;

        return $this;
    }

    /** @return string[] */
    protected function getAvailableIps(): array
    {
        return $this->availableIps;
    }

    /** @return string[] */
    protected function getOldIps(): array
    {
        if (!empty($this->oldIps)) {
            return $this->oldIps;
        }

        if ($this->nodeId) {
            return Allocation::where('node_id', $this->nodeId)
                ->pluck('ip')
                ->unique()
                ->values()
                ->all();
        }

        return [];
    }
}
