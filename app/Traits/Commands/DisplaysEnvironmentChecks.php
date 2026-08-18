<?php

namespace App\Traits\Commands;

use App\Enums\EnvironmentCheckStatus;
use App\ValueObjects\EnvironmentCheckResult;

trait DisplaysEnvironmentChecks
{
    /** @param EnvironmentCheckResult[] $results */
    protected function displayEnvironmentChecks(array $results): void
    {
        $this->table(
            [
                trans('commands.environment_check.check'),
                trans('commands.environment_check.status'),
                trans('commands.environment_check.details'),
            ],
            array_map(fn (EnvironmentCheckResult $result) => [
                $result->label,
                $this->formatEnvironmentCheckStatus($result->status),
                $result->message . ($result->remediation ? "\n" . $result->remediation : ''),
            ], $results),
        );
    }

    private function formatEnvironmentCheckStatus(EnvironmentCheckStatus $status): string
    {
        return match ($status) {
            EnvironmentCheckStatus::Passed => '<fg=green>' . trans('commands.environment_check.passed') . '</>',
            EnvironmentCheckStatus::Warning => '<fg=yellow>' . trans('commands.environment_check.warning') . '</>',
            EnvironmentCheckStatus::Failed => '<fg=red>' . trans('commands.environment_check.failed') . '</>',
        };
    }
}
