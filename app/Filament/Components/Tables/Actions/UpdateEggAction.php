<?php

namespace App\Filament\Components\Tables\Actions;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions\StaticAction;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class UpdateEggAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'update';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans_choice('admin/egg.update', 1));

        $this->icon('tabler-cloud-download');

        $this->color('success');

        $this->requiresConfirmation();

        $this->modalHeading(trans_choice('admin/egg.update_question', 1));

        $this->modalDescription(trans_choice('admin/egg.update_description', 1));

        $this->modalIconColor('danger');

        $this->modalSubmitAction(fn (StaticAction $action) => $action->color('danger'));

        $this->action(function (Egg $egg, EggImporterService $eggImporterService) {
            try {
                $eggImporterService->fromUrl($egg->update_url, $egg);

                cache()->forget("eggs.$egg->uuid.update");
            } catch (Exception $exception) {
                Notification::make()
                    ->title(trans('admin/egg.update_failed'))
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);

                return;
            }

            Notification::make()
                ->title(trans_choice('admin/egg.updated', 1))
                ->body($egg->name)
                ->success()
                ->send();
        });

        $this->authorize(fn () => auth()->user()->can('import egg'));

        $this->visible(fn (Egg $egg) => cache()->get("eggs.$egg->uuid.update", false));
    }
}
