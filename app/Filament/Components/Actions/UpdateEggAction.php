<?php

namespace App\Filament\Components\Actions;

use App\Models\Egg;
use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;

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

        $this->iconButton();

        $this->icon('tabler-cloud-download');

        $this->iconSize(IconSize::ExtraLarge);

        $this->color('success');

        $this->requiresConfirmation();

        $this->modalHeading(trans_choice('admin/egg.update_question', 1));

        $this->modalDescription(trans_choice('admin/egg.update_description', 1));

        $this->modalIconColor('danger');

        $this->modalSubmitAction(fn (Action $action) => $action->color('danger'));

        $this->action(function (Egg $egg, EggImporterService $eggImporterService) {
            try {
                $eggImporterService->fromUrl($egg->update_url, $egg);

                cache()->forget("eggs.$egg->uuid.update");
            } catch (Exception $exception) {
                Notification::make()
                    ->title(trans('admin/egg.update_failed', ['egg' => $egg->name]))
                    ->body(trans('admin/egg.update_error', ['error' => $exception->getMessage()]))
                    ->danger()
                    ->send();

                report($exception);

                return;
            }

            Notification::make()
                ->title(trans('admin/egg.update_success', ['egg' => $egg->name]))
                ->body(trans('admin/egg.updated_from', ['url' => $egg->update_url]))
                ->success()
                ->send();
        });

        $this->authorize(fn () => user()?->can('import egg'));

        $this->visible(fn (Egg $egg) => cache()->get("eggs.$egg->uuid.update", false));
    }
}
