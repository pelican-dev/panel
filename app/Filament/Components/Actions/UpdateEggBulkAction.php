<?php

namespace App\Filament\Components\Actions;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
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

        $this->modalSubmitAction(fn (Action $action) => $action->color('danger'));

        $this->action(function (Collection $records, EggImporterService $eggImporterService) {
            if ($records->count() === 0) {
                Notification::make()
                    ->title(trans('admin/egg.no_updates'))
                    ->warning()
                    ->send();

                return;
            }

            $success = 0;
            $failed = 0;

            /** @var Egg $egg */
            foreach ($records as $egg) {
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
                ->body($failed > 0 ? trans('admin/egg.updated_failed', ['count' => $failed]) : null)
                ->status($failed > 0 ? 'warning' : 'success')
                ->persistent()
                ->send();
        });

        $this->authorize(fn () => user()?->can('import egg'));

        $this->deselectRecordsAfterCompletion();
    }
}
