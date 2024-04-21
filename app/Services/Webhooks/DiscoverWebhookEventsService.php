<?php

namespace App\Services\Webhooks;

use App\Events\ShouldDispatchWebhooks;
use Illuminate\Support\Collection;
use Spatie\StructureDiscoverer\Discover;

class DiscoverWebhookEventsService
{
    public static function toFilamentCheckboxList(): array
    {
        return static::discover()->mapWithKeys(function ($item) {
            $withoutPrefix = str_replace('App\\', '', $item);

            return [$item => $withoutPrefix];
        })->toArray();
    }

    public static function discover(): Collection
    {
        return collect(Discover::in(app_path('Events'))
            ->classes()
            ->implementing(ShouldDispatchWebhooks::class)->get());
    }

}
