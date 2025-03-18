<?php

namespace App\Filament\Components\Tables\Actions;

use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions\StaticAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;

class UpdateEggBulkAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'update';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans_choice('admin/egg.update', 2));

        $this->icon('tabler-cloud-download');

        $this->color('success');

        $this->requiresConfirmation();

        $this->modalHeading(trans_choice('admin/egg.update_question', 2));

        $this->modalDescription(trans_choice('admin/egg.update_description', 2));

        $this->modalIconColor('danger');

        $this->modalSubmitAction(fn (StaticAction $action) => $action->color('danger'));

        $this->action(function (Collection $records, EggImporterService $eggImporterService) {
            $success = 0;
            $failed = 0;
            $skipped = 0;

            foreach ($records as $egg) {
                if (!cache()->get("eggs.$egg->uuid.update", false)) {
                    $skipped++;

                    continue;
                }

                try {
                    $eggImporterService->fromUrl($egg->update_url, $egg);

                    $success++;

                    cache()->forget("eggs.$egg->uuid.update");
                } catch (Exception $exception) {
                    $failed++;

                    report($exception);
                }
            }

            Notification::make()
                ->title(trans_choice('admin/egg.updated', 2, ['count' => $success, 'total' => $records->count()]))
                ->body(trans('admin/egg.updated_failed', ['count' => $failed]) . ', ' . trans('admin/egg.updated_skipped', ['count' => $skipped]))
                ->status($failed > 0 ? 'warning' : 'success')
                ->persistent()
                ->send();
        });

        $this->authorize(fn () => auth()->user()->can('import egg'));

        $this->deselectRecordsAfterCompletion();
    }
}
