<?php

namespace App\Enums;

use App\Models\Server;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\RateLimiter;
use Webmozart\Assert\Assert;

/**
 * A basic resource throttler for individual servers. This is applied in addition
 * to existing rate limits and allows the code to slow down speedy users that might
 * be creating resources a little too quickly for comfort. This throttle generally
 * only applies to creation flows, and not general view/edit/delete flows.
 */
enum ResourceLimit: string
{
    case Websocket = 'websocket';
    case AllocationCreate = 'allocation-create';
    case BackupRestore = 'backup-restore';
    case DatabaseCreate = 'database-create';
    case ScheduleCreate = 'schedule-create';
    case SubuserCreate = 'subuser-create';
    case FilePull = 'file-pull';

    public function throttleKey(): string
    {
        return "api.client:server-resource:{$this->name}";
    }

    /**
     * Returns a middleware that will throttle the specific resource by server. This
     * throttle applies to any user making changes to that resource on the specific
     * server, it is NOT per-user.
     */
    public function middleware(): string
    {
        return ThrottleRequests::using($this->throttleKey());
    }

    public function limit(): Limit
    {
        return match ($this) {
            self::Websocket => Limit::perMinute(5),
            self::BackupRestore => Limit::perMinutes(15, 3),
            self::DatabaseCreate => Limit::perMinute(2),
            self::SubuserCreate => Limit::perMinutes(15, 10),
            self::FilePull => Limit::perMinutes(10, 5),
            default => Limit::perMinute(2),
        };
    }

    public static function boot(): void
    {
        foreach (self::cases() as $case) {
            RateLimiter::for($case->throttleKey(), function (Request $request) use ($case) {
                Assert::isInstanceOf($server = $request->route()->parameter('server'), Server::class);

                return $case->limit()->by($server->uuid);
            });
        }
    }
}
