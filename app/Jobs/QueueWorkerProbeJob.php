<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;

class QueueWorkerProbeJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(private readonly string $token) {}

    public function handle(): void
    {
        $path = self::markerPath($this->token);

        File::ensureDirectoryExists(dirname($path));
        File::put($path, now()->toIso8601String());
    }

    public static function markerPath(string $token): string
    {
        $safeToken = preg_replace('/[^a-zA-Z0-9-]/', '', $token);

        return storage_path("framework/cache/pelican-queue-probes/{$safeToken}");
    }
}
