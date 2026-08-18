<?php

namespace App\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class InstallationFlagCheck extends Check
{
    public function run(): Result
    {
        $result = Result::make()->meta([
            'remediation' => trans('installer.health.installed.remediation'),
        ]);

        return config('app.installed')
            ? $result->ok(trans('installer.health.installed.passed'))
            : $result->failed(trans('installer.health.installed.failed'));
    }
}
