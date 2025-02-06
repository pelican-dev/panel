<?php

namespace App\Checks;

use Illuminate\Support\Facades\App;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class EnvironmentCheck extends Check
{
    protected string $expectedEnvironment = 'production';

    public function expectEnvironment(string $expectedEnvironment): self
    {
        $this->expectedEnvironment = $expectedEnvironment;

        return $this;
    }

    public function run(): Result
    {
        $actualEnvironment = (string) App::environment();

        $result = Result::make()
            ->meta([
                'actual' => $actualEnvironment,
                'expected' => $this->expectedEnvironment,
            ])
            ->shortSummary($actualEnvironment);

        return $this->expectedEnvironment === $actualEnvironment
            ? $result->ok(trans('admin/health.results.environment.ok'))
            : $result->failed(trans('admin/health.results.environment.failed', [
                'actual' => $actualEnvironment,
                'expected' => $this->expectedEnvironment,
            ]));
    }
}
