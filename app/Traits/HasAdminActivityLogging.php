<?php

namespace App\Traits;

use App\Observers\AdminActivityObserver;

trait HasAdminActivityLogging
{
    public static function bootHasAdminActivityLogging(): void
    {
        $observer = new AdminActivityObserver();

        static::created(fn ($model) => $observer->created($model));
        static::updated(fn ($model) => $observer->updated($model));
        static::deleted(fn ($model) => $observer->deleted($model));
    }

    public function getAdminActivityName(): string
    {
        if (isset($this->attributes['name'])) {
            return (string) $this->attributes['name'];
        }

        return (string) $this->getKey();
    }
}
