<?php

namespace App\Jobs;

use App\Services\Eggs\Sharing\EggImporterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class InstallEgg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 15;

    public function __construct(public string $downloadUrl) {}

    /**
     * @throws Throwable
     */
    public function handle(EggImporterService $eggImporterService): void
    {
        try {
            $eggImporterService->fromUrl($this->downloadUrl);
        } catch (Throwable $e) {
            Log::error('Failed to install egg from URL: ' . $this->downloadUrl, ['exception' => $e]);
        }
    }
}
