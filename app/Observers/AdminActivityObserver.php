<?php

namespace App\Observers;

use App\Facades\Activity;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminActivityObserver
{
    /**
     * Tracks events already logged in this request to avoid duplicates.
     *
     * @var array<string, bool>
     */
    private static array $logged = [];

    /**
     * Determines if the current request is being handled by the admin panel.
     */
    private function isAdminPanel(): bool
    {
        return Filament::getCurrentPanel()?->getId() === 'admin';
    }

    /**
     * Logs an admin activity event for the given model, deduplicating within a single request.
     *
     * @param  string  $event  The event name (e.g. 'admin:user.create')
     * @param  Model  $model  The model being acted upon
     * @param  array<string, mixed>  $properties  Additional properties to log
     *
     * @throws \Throwable
     */
    private function log(string $event, Model $model, array $properties = []): void
    {
        if (!$this->isAdminPanel()) {
            return;
        }

        $actor = user();
        if (!$actor) {
            return;
        }

        // Deduplicate identical events for the same record within a single request.
        $key = $event . ':' . $model::class . ':' . $model->getKey();
        if (isset(self::$logged[$key])) {
            return;
        }
        self::$logged[$key] = true;

        $log = Activity::event($event)
            ->actor($actor)
            ->subject($model);

        foreach ($properties as $propKey => $propValue) {
            $log->property($propKey, $propValue);
        }

        $log->log();
    }

    public function created(Model $model): void
    {
        $this->log($this->eventFor($model, 'create'), $model, [
            'name' => $this->displayNameFor($model),
        ]);
    }

    public function updated(Model $model): void
    {
        $changedFields = $this->changedFieldsFor($model);
        $name = $this->displayNameFor($model);

        $this->log($this->eventFor($model, 'update'), $model, [
            'name' => empty($changedFields) ? $name : sprintf('%s (%s)', $name, implode(', ', $changedFields)),
            'count' => count($changedFields),
            'changes' => implode(', ', $changedFields),
        ]);
    }

    public function deleted(Model $model): void
    {
        $this->log($this->eventFor($model, 'delete'), $model, [
            'name' => $this->displayNameFor($model),
        ]);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function eventFor(Model $model, string $action): string
    {
        return sprintf('admin:%s.%s', $this->resourceNameFor($model), $action);
    }

    private function resourceNameFor(Model $model): string
    {
        $constant = $model::class . '::RESOURCE_NAME';

        if (defined($constant)) {
            $value = constant($constant);

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return Str::of(class_basename($model))->snake()->toString();
    }

    private function displayNameFor(Model $model): string
    {
        if (method_exists($model, 'getAdminActivityName')) {
            return $model->getAdminActivityName();
        }

        return (string) $model->getKey();
    }

    /**
     * Returns the sorted list of attribute names that changed on the given model,
     * excluding internal timestamps.
     *
     * @return string[]
     */
    private function changedFieldsFor(Model $model): array
    {
        $fields = collect(array_keys($model->getChanges()))
            ->reject(fn (string $field) => $field === 'updated_at')
            ->values()
            ->all();

        sort($fields);

        return $fields;
    }
}
