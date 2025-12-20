<?php

namespace App\Filament\Components\Actions;

use App\Models\Server;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Storage;

class DeleteServerIcon extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'delete_icon';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(fn ($record) => $record->icon);

        $this->hiddenLabel();

        $this->icon('tabler-trash');

        $this->iconButton();

        $this->iconSize(IconSize::Large);

        $this->color('danger');

        $this->action(function ($record) {
            foreach (array_keys(Server::IMAGE_FORMATS) as $ext) {
                $path = Server::ICON_STORAGE_PATH . "/$record->uuid.$ext";
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            Notification::make()
                ->title(trans('server/setting.server_info.icon.deleted'))
                ->success()
                ->send();

            $record->refresh();
        });
    }
}
