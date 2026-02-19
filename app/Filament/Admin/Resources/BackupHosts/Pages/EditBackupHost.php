<?php

namespace App\Filament\Admin\Resources\BackupHosts\Pages;

use App\Filament\Admin\Resources\BackupHosts\BackupHostResource;
use App\Models\BackupHost;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\IconSize;

/** @property BackupHost $record */
class EditBackupHost extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = BackupHostResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(fn (BackupHost $backupHost) => $backupHost->backups()->count() > 0 ? trans('admin/backuphost.delete_help') : trans('filament-actions::delete.single.modal.actions.delete.label'))
                ->disabled(fn (BackupHost $backupHost) => $backupHost->backups()->count() > 0)
                ->hidden(fn () => BackupHost::count() === 1)
                ->iconButton()
                ->iconSize(IconSize::ExtraLarge),
            $this->getSaveFormAction()
                ->formId('form')
                ->iconButton()
                ->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy'),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
