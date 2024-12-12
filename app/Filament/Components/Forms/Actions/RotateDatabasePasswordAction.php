<?php

namespace App\Filament\Components\Forms\Actions;

use App\Models\Database;
use App\Services\Databases\DatabasePasswordService;
use Exception;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Notifications\Notification;

class RotateDatabasePasswordAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'rotate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Rotate');

        $this->icon('tabler-refresh');

        $this->authorize(fn (Database $database) => auth()->user()->can('update database', $database));

        $this->modalHeading('Rotate Password');

        $this->modalIconColor('warning');

        $this->modalSubmitAction(fn (StaticAction $action) => $action->color('warning'));

        $this->requiresConfirmation();

        $this->action(function (DatabasePasswordService $service, Database $database, Set $set) {
            try {
                $service->handle($database);

                $database->refresh();

                $set('password', $database->password);
                $set('jdbc', $database->jdbc);

                Notification::make()
                    ->title('Password rotated')
                    ->success()
                    ->send();
            } catch (Exception $exception) {
                Notification::make()
                    ->title('Password rotation failed')
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);
            }
        });
    }
}
