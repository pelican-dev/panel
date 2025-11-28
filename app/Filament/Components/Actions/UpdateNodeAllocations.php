<?php

namespace App\Filament\Components\Actions;

use App\Models\Allocation;
use App\Models\Node;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;

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
        $this->iconSize(IconSize::ExtraLarge);
        $this->iconButton();

        $this->color('warning');

        $this->requiresConfirmation();

        $this->modalHeading(trans('admin/node.bulk_update_ip'));

        $this->modalDescription(trans('admin/node.bulk_update_ip_description'));

        $this->modalIconColor('warning');

        $this->modalSubmitActionLabel(trans('admin/node.update_ip'));

        $this->schema(function () {
            /** @var Node $node */
            $node = $this->record;

            $currentIps = Allocation::where('node_id', $node->id)
                ->pluck('ip')
                ->unique()
                ->values()
                ->all();

            return [
                Select::make('old_ip')
                    ->label(trans('admin/node.old_ip'))
                    ->options(array_combine($currentIps, $currentIps))
                    ->selectablePlaceholder(false)
                    ->required()
                    ->live(),
                Select::make('new_ip')
                    ->label(trans('admin/node.new_ip'))
                    ->options(fn () => array_combine($node->ipAddresses(), $node->ipAddresses()) ?: [])
                    ->required()
                    ->different('old_ip'),
            ];
        });

        $this->action(function (array $data) {
            /** @var Node $node */
            $node = $this->record;
            $allocations = Allocation::where('node_id', $node->id)->where('ip', $data['old_ip'])->get();

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
                    $allocation->update(['ip' => $data['new_ip']]);
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

    public function nodeRecord(Node $node): static
    {
        $this->record = $node;

        return $this;
    }
}
