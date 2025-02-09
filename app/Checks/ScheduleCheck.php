<?php

namespace App\Checks;

use Carbon\Carbon;
use Composer\InstalledVersions;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class ScheduleCheck extends Check
{
    protected string $cacheKey = 'health:checks:schedule:latestHeartbeatAt';

    protected ?string $cacheStoreName = null;

    protected int $heartbeatMaxAgeInMinutes = 1;

    public function useCacheStore(string $cacheStoreName): self
    {
        $this->cacheStoreName = $cacheStoreName;

        return $this;
    }

    public function getCacheStoreName(): string
    {
        return $this->cacheStoreName ?? config('cache.default');
    }

    public function cacheKey(string $cacheKey): self
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    public function heartbeatMaxAgeInMinutes(int $heartbeatMaxAgeInMinutes): self
    {
        $this->heartbeatMaxAgeInMinutes = $heartbeatMaxAgeInMinutes;

        return $this;
    }

    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    public function run(): Result
    {
        $result = Result::make()->ok(trans('admin/health.results.schedule.ok'));

        $lastHeartbeatTimestamp = cache()->store($this->cacheStoreName)->get($this->cacheKey);

        if (!$lastHeartbeatTimestamp) {
            return $result->failed(trans('admin/health.results.schedule.failed_not_ran'));
        }

        $latestHeartbeatAt = Carbon::createFromTimestamp($lastHeartbeatTimestamp);

        $carbonVersion = InstalledVersions::getVersion('nesbot/carbon');

        $minutesAgo = $latestHeartbeatAt->diffInMinutes();

        if (version_compare($carbonVersion,
            '3.0.0', '<')) {
            $minutesAgo += 1;
        }

        if ($minutesAgo > $this->heartbeatMaxAgeInMinutes) {
            return $result->failed(trans('admin/health.results.schedule.failed_last_ran', [
                'time' => $minutesAgo,
            ]));
        }

        return $result;
    }
}
