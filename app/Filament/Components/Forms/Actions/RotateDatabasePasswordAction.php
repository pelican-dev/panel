<?php

namespace App\Filament\Components\Forms\Actions;

use App\Facades\Activity;
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

        $this->label(trans('admin/databasehost.rotate'));

        $this->icon('tabler-refresh');

        $this->authorize(fn (Database $database) => auth()->user()->can('update', $database));

        $this->modalHeading(trans('admin/databasehost.rotate_password'));

        $this->modalIconColor('warning');

        $this->modalSubmitAction(fn (StaticAction $action) => $action->color('warning'));

        $this->requiresConfirmation();

        $this->action(function (DatabasePasswordService $service, Database $database, Set $set) {
            try {
                $service->handle($database);

                $database->refresh();

                $set('password', $database->password);
                $set('jdbc', $database->jdbc);

                Activity::event('server:database.rotate-password')
                    ->subject($database)
                    ->property('name', $database->database)
                    ->log();

                Notification::make()
                    ->title(trans('admin/databasehost.rotated'))
                    ->success()
                    ->send();
            } catch (Exception $exception) {
                Notification::make()
                    ->title(trans('admin/databasehost.rotate_error'))
                    ->body($exception->getMessage())
                    ->danger()
                    ->send();

                report($exception);
            }
        });
    }
}
