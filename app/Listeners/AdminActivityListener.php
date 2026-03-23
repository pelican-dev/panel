<?php

namespace App\Listeners;

use App\Facades\Activity;
use Filament\Facades\Filament;
use Filament\Resources\Events\RecordCreated;
use Filament\Resources\Events\RecordUpdated;
use Illuminate\Support\Str;

class AdminActivityListener
{
    protected const REDACTED_FIELDS = [
        'password',
        'password_confirmation',
        'current_password',
        'token',
        'secret',
        'api_key',
        'daemon_token',
        '_token',
    ];

    public function handle(RecordCreated|RecordUpdated $event): void
    {
        if (Filament::getCurrentPanel()?->getId() !== 'admin') {
            return;
        }

        $record = $event->getRecord();
        $page = $event->getPage();
        $data = $event->getData();

        $resourceClass = $page::getResource();
        $modelClass = $resourceClass::getModel();
        $slug = Str::kebab(class_basename($modelClass));

        $action = $event instanceof RecordCreated ? 'create' : 'update';

        $properties = $this->redactSensitiveFields($data);

        Activity::event("admin:$slug.$action")
            ->subject($record)
            ->property($properties)
            ->log();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function redactSensitiveFields(array $data): array
    {
        $redacted = [];

        foreach ($data as $key => $value) {
            if (in_array($key, self::REDACTED_FIELDS, true)) {
                $redacted[$key] = '[REDACTED]';

                continue;
            }

            if (is_array($value)) {
                $redacted[$key] = $this->redactSensitiveFields($value);
            } else {
                $redacted[$key] = $value;
            }
        }

        return $redacted;
    }
}
