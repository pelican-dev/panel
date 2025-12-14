<?php

namespace App\Jobs;

use App\Services\Eggs\Sharing\EggImporterService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class InstallEgg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $downloadUrl;

    public ?int $tries = 3;

    public ?int $timeout = 60;

    public function __construct(string $downloadUrl)
    {
        $this->downloadUrl = $downloadUrl;
    }

    public function handle(EggImporterService $eggImporterService): void
    {
        try {
            $eggImporterService->fromUrl($this->downloadUrl);
        } catch (Exception $exception) {
            Log::error('InstallEgg job failed for ' . $this->downloadUrl . ': ' . $exception->getMessage());

            throw $exception;
        }
    }
}
