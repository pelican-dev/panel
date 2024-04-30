<?php

namespace App\Services\Webhooks;

use App\Events\ShouldDispatchWebhooks;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use ReflectionClass;

class DiscoverWebhookEventsService
{
    public static function toFilamentCheckboxList(): array
    {
        return static::discover()->mapWithKeys(function ($item) {
            $withoutPrefix = str_replace('App\\', '', $item);

            return [$item => $withoutPrefix];
        })->toArray();
    }

    private static function discover(): Collection
    {
        $events = collect();
        $eventsPath = app_path('Events');

        if (!File::isDirectory($eventsPath)) {
            return $events;
        }

        $files = File::allFiles($eventsPath);

        foreach ($files as $file) {
            $class = app()->getNamespace() . str_replace(
                ['/', '.php'],
                ['\\', ''],
                str_after($file->getPathname(), realpath(app_path()) . DIRECTORY_SEPARATOR)
            );

            if (!class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            if ($reflection->isAbstract()) {
                continue;
            }

            if ($reflection->implementsInterface(ShouldDispatchWebhooks::class)) {
                $events->push($class);
            }
        }

        return $events;
    }

}
