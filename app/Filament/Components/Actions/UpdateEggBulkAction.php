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

            $successEggs = collect();
            $failedEggs = collect();
            $skippedEggs = collect();

            /** @var Egg $egg */
            foreach ($records as $egg) {
                if ($egg->update_url === null) {
                    $skippedEggs->push($egg->name);

                    continue;
                }
                try {
                    $eggImporterService->fromUrl($egg->update_url, $egg);

                    $successEggs->push($egg->name);

                    cache()->forget("eggs.$egg->uuid.update");
                } catch (Exception $exception) {
                    $failedEggs->push($egg->name);

                    report($exception);
                }
            }

            $bodyParts = collect([
                $successEggs->isNotEmpty() ? trans('admin/egg.updated_eggs', ['eggs' => $successEggs->join(', ')]) : null,
                $failedEggs->isNotEmpty() ? trans('admin/egg.failed_eggs', ['eggs' => $failedEggs->join(', ')]) : null,
                $skippedEggs->isNotEmpty() ? trans('admin/egg.skipped_eggs', ['eggs' => $skippedEggs->join(', ')]) : null,
            ])->filter();

            Notification::make()
                ->title(trans_choice('admin/egg.updated', 2, ['count' => $successEggs->count(), 'total' => $records->count()]))
                ->body($bodyParts->join(' | '))
                ->status($failedEggs->isNotEmpty() ? 'warning' : 'success')
                ->persistent()
                ->send();
        });

        $this->authorize(fn () => user()?->can('import egg'));

        $this->deselectRecordsAfterCompletion();
    }
}
