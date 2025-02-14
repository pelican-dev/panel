<?php

namespace App\Checks;

use Carbon\Carbon;
use Composer\InstalledVersions;
use Spatie\Health\Checks\Checks\ScheduleCheck as BaseCheck;
use Spatie\Health\Checks\Result;

class ScheduleCheck extends BaseCheck
{
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
