<?php

namespace App\Services\Environment;

use App\Enums\EnvironmentCheckStatus;
use App\Jobs\QueueWorkerProbeJob;
use App\ValueObjects\EnvironmentCheckResult;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class QueueWorkerProbeService
{
    public function check(?string $connection = null, int $timeoutSeconds = 10): EnvironmentCheckResult
    {
        $connection ??= config('queue.default');

        if (!is_string($connection) || $connection === '') {
            return $this->failure(trans('installer.health.queue.not_configured'));
        }

        $token = (string) Str::uuid();
        $marker = QueueWorkerProbeJob::markerPath($token);

        try {
            File::ensureDirectoryExists(dirname($marker));
            $this->pruneStaleMarkers(dirname($marker));

            QueueWorkerProbeJob::dispatch($token)->onConnection($connection);

            $deadline = microtime(true) + max(0, $timeoutSeconds);
            do {
                if (File::exists($marker)) {
                    File::delete($marker);

                    return new EnvironmentCheckResult(
                        'queue',
                        trans('installer.health.queue.label'),
                        EnvironmentCheckStatus::Passed,
                        trans('installer.health.queue.passed', ['connection' => $connection]),
                    );
                }

                usleep(100_000);
            } while (microtime(true) < $deadline);
        } catch (Throwable $exception) {
            return $this->failure(trans('installer.health.queue.exception', ['error' => $exception->getMessage()]));
        } finally {
            File::delete($marker);
        }

        return $this->failure(trans('installer.health.queue.timed_out', [
            'connection' => $connection,
            'seconds' => $timeoutSeconds,
        ]));
    }

    private function failure(string $message): EnvironmentCheckResult
    {
        return new EnvironmentCheckResult(
            'queue',
            trans('installer.health.queue.label'),
            EnvironmentCheckStatus::Failed,
            $message,
            trans('installer.health.queue.remediation'),
        );
    }

    private function pruneStaleMarkers(string $directory): void
    {
        foreach (File::files($directory) as $file) {
            if ($file->getMTime() < now()->subHour()->getTimestamp()) {
                File::delete($file->getPathname());
            }
        }
    }
}
