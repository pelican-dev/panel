<?php

namespace App\Filament\Admin\Resources\Webhooks\Pages;

use App\Enums\TablerIcon;
use App\Facades\WebhookTypes;
use App\Filament\Admin\Resources\Webhooks\WebhookResource;
use App\Models\WebhookConfiguration;
use App\Traits\Filament\CanCustomizeHeaderActions;
use App\Traits\Filament\CanCustomizeHeaderWidgets;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebhookConfiguration extends EditRecord
{
    use CanCustomizeHeaderActions;
    use CanCustomizeHeaderWidgets;

    protected static string $resource = WebhookResource::class;

    /** @return array<Action|ActionGroup> */
    protected function getDefaultHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('test_now')
                ->tooltip(trans('admin/webhook.test_now'))
                ->color('primary')
                ->disabled(fn (WebhookConfiguration $webhookConfiguration) => count($webhookConfiguration->events) === 0)
                ->action(fn (WebhookConfiguration $webhookConfiguration) => $webhookConfiguration->run())
                ->icon(TablerIcon::TestPipe),
            Action::make('save')
                ->hiddenLabel()
                ->action('save')
                ->keyBindings(['mod+s'])
                ->tooltip(trans('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->icon(TablerIcon::DeviceFloppy),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($schema = WebhookTypes::get($data['type'] ?? null)) {
            $data = $schema->mutateFormDataBeforeSave($data);
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($schema = WebhookTypes::get($data['type'] ?? null)) {
            $data = $schema->mutateFormDataBeforeFill($data);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->dispatch('refresh-widget');
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        WebhookResource::sendHelpBanner();
    }
}
