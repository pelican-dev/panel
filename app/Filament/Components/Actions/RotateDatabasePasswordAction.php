<?php

namespace App\Filament\Components\Actions;

use App\Facades\Activity;
use App\Models\Database;
use App\Services\Databases\DatabaseManagementService;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;

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

        $this->authorize(fn (Database $database) => user()?->can('update', $database));

        $this->modalHeading(trans('admin/databasehost.rotate_password'));

        $this->modalIconColor('warning');

        $this->modalSubmitAction(fn (Action $action) => $action->color('warning'));

        $this->requiresConfirmation();

        $this->action(function (DatabaseManagementService $service, Database $database, Set $set) {
            try {
                $service->rotatePassword($database);
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
                    ->body(fn () => user()?->canAccessPanel(Filament::getPanel('admin')) ? $exception->getMessage() : null)
                    ->danger()
                    ->send();

                report($exception);
            }
        });
    }
}
