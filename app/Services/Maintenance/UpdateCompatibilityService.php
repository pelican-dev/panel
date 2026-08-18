<?php

namespace App\Services\Maintenance;

use App\Enums\EnvironmentCheckStatus;
use App\ValueObjects\EnvironmentCheckResult;
use Illuminate\Support\Facades\Process;
use Throwable;

class UpdateCompatibilityService
{
    public function check(string $source): EnvironmentCheckResult
    {
        $composerJson = $source . DIRECTORY_SEPARATOR . 'composer.json';
        $composerLock = $source . DIRECTORY_SEPARATOR . 'composer.lock';

        if (!is_file($composerJson) || !is_file($composerLock)) {
            return $this->failure(trans('commands.update.compatibility_files_missing'));
        }

        try {
            $result = Process::path($source)
                ->timeout(120)
                ->run(['composer', 'check-platform-reqs', '--lock', '--no-dev']);
        } catch (Throwable $exception) {
            return $this->failure(trans('commands.update.compatibility_exception', ['error' => $exception->getMessage()]));
        }

        if ($result->failed()) {
            $details = trim($result->errorOutput() . "\n" . $result->output());

            return $this->failure(trans('commands.update.compatibility_command_failed', [
                'details' => $details !== '' ? $details : trans('commands.update.no_command_output'),
            ]));
        }

        return new EnvironmentCheckResult(
            'update_compatibility',
            trans('commands.update.compatibility_label'),
            EnvironmentCheckStatus::Passed,
            trans('commands.update.compatibility_passed'),
        );
    }

    private function failure(string $message): EnvironmentCheckResult
    {
        return new EnvironmentCheckResult(
            'update_compatibility',
            trans('commands.update.compatibility_label'),
            EnvironmentCheckStatus::Failed,
            $message,
            trans('commands.update.compatibility_remediation'),
        );
    }
}
