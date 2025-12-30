<?php

namespace App\Filament\Admin\Resources\Eggs\Pages;

use App\Filament\Admin\Resources\Eggs\EggResource;
use App\Filament\Components\Actions\ExportEggAction;
use App\Filament\Components\Actions\ImportEggAction;
use App\Models\Egg;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Exception;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Storage;

class EditEgg extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = EggResource::class;

    public function form(Schema $schema): Schema
    {
        return EggResource::schema($schema);
    }

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->disabled(fn (Egg $egg): bool => $egg->servers()->count() > 0)
                ->label(fn (Egg $egg): string => $egg->servers()->count() <= 0 ? trans('filament-actions::delete.single.label') : trans('admin/egg.in_use'))
                ->iconButton()->iconSize(IconSize::ExtraLarge),
            ExportEggAction::make(),
            ImportEggAction::make()
                ->multiple(false),
            $this->getSaveFormAction()->formId('form')
                ->iconButton()->iconSize(IconSize::ExtraLarge)
                ->icon('tabler-device-floppy'),
        ];
    }

    public function refreshForm(): void
    {
        $this->fillForm();
    }

    /**
     * Save an image from URL download to a file.
     *
     * @throws Exception
     */
    private function saveImageFromUrl(string $imageUrl, string $extension, Egg $egg): void
    {
        $context = stream_context_create([
            'http' => ['timeout' => 3],
            'https' => [
                'timeout' => 3,
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $data = @file_get_contents($imageUrl, false, $context, 0, 1048576); // 1024KB

        if (empty($data)) {
            throw new Exception(trans('admin/egg.import.invalid_url'));
        }

        $normalizedExtension = match ($extension) {
            'svg+xml' => 'svg',
            'jpeg' => 'jpg',
            default => $extension,
        };

        Storage::disk('public')->put(Egg::ICON_STORAGE_PATH . "/$egg->uuid.$normalizedExtension", $data);
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
