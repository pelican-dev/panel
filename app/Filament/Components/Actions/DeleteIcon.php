<?php

namespace App\Filament\Components\Actions;

use App\Enums\TablerIcon;
use App\Models\Traits\HasIcon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class DeleteIcon extends Action
{
    /** @var string[] */
    protected ?array $iconFormats = null;

    protected ?string $iconStoragePath = null;

    public static function getDefaultName(): ?string
    {
        return 'delete_icon';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(fn ($record) => $record->icon);

        $this->hiddenLabel();

        $this->tooltip(trans('admin/egg.import.delete_icon'));

        $this->icon(TablerIcon::Trash);

        $this->color('danger');

        $this->action(function ($record) {
            foreach ($this->getIconFormats() as $ext) {
                $path = $this->getIconStoragePath() . "/$record->uuid.$ext";
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            Notification::make()
                ->title(trans('admin/egg.import.icon_deleted'))
                ->success()
                ->send();

            $record->refresh();
        });
    }

    /** @param string[] $iconFormats */
    public function iconFormats(?array $iconFormats): static
    {
        $this->iconFormats = $iconFormats;

        return $this;
    }

    public function iconStoragePath(?string $iconStoragePath): static
    {
        $this->iconStoragePath = $iconStoragePath;

        return $this;
    }

    /** @return string[] */
    public function getIconFormats(): array
    {
        return $this->iconFormats ?? array_keys(HasIcon::$iconFormats);
    }

    public function getIconStoragePath(): ?string
    {
        return $this->iconStoragePath;
    }
}
