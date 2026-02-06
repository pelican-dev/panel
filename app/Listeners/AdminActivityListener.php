<?php

namespace App\Listeners;

use App\Facades\Activity;
use Filament\Facades\Filament;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminActivityListener
{
    protected const REDACTED_FIELDS = [
        'password',
        'password_confirmation',
        'token',
        'secret',
        'api_key',
    ];

    /** @param array<string, mixed> $data */
    public function handle(Model $record, array $data, Page $page): void
    {
        if (Filament::getCurrentPanel()?->getId() !== 'admin') {
            return;
        }

        $resourceClass = $page::getResource();
        $modelClass = $resourceClass::getModel();
        $slug = Str::kebab(class_basename($modelClass));

        $action = $page instanceof \Filament\Resources\Pages\CreateRecord ? 'create' : 'update';

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
