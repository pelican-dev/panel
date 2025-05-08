<?php

namespace App\Filament\Admin\Resources\WebhookResource\Pages;

use App\Filament\Admin\Resources\WebhookResource;
use App\Models\WebhookConfiguration;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWebhookConfiguration extends CreateRecord
{
    protected static string $resource = WebhookResource::class;

    public function mount(): void
    {
        $this->authorizeAccess();

        $webhook = new WebhookConfiguration();
        $webhook->type = 'standalone';
        $webhook->description = 'New Webhook ' . now()->format('Y-m-d H:i:s');
        $webhook->endpoint = 'https://example.com/webhook';
        $webhook->events = ['server:created']; // To fill one event so it doesn't throw an error, not actually used..
        $webhook->save();

        // Wip, form for creation wasn't saving but edit was, easier to create a webhook and redirect to edit.
        $this->redirect(EditWebhookConfiguration::getUrl(['record' => $webhook]));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
}