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

        $this->label('Update');

        $this->icon('tabler-cloud-download');

        $this->color('success');

        $this->requiresConfirmation();

        $this->modalHeading('Are you sure you want to update this egg?');

        $this->modalDescription('If you made any changes to the egg they will be overwritten!');

        $this->modalIconColor('danger');

        $this->modalSubmitAction(fn (StaticAction $action) => $action->color('danger'));

        $this->action(function (Egg $egg, EggImporterService $eggImporterService) {
            try {
                $eggImporterService->fromUrl($egg->update_url, $egg);

                cache()->forget("eggs.$egg->uuid.update");
            } catch (Exception $exception) {
                Notification::make()
                    ->title('Egg Update failed')
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);

                return;
            }

            Notification::make()
                ->title('Egg updated')
                ->body($egg->name)
                ->success()
                ->send();
        });

        $this->authorize(fn () => auth()->user()->can('import egg'));

        $this->visible(fn (Egg $egg) => cache()->get("eggs.$egg->uuid.update", false));
    }
}
