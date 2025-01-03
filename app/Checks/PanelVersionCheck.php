<?php

namespace App\Checks;

use App\Services\Helpers\SoftwareVersionService;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class PanelVersionCheck extends Check
{
    public function __construct(private SoftwareVersionService $versionService) {}

    public function run(): Result
    {
        $isLatest = $this->versionService->isLatestPanel();
        $currentVersion = $this->versionService->currentPanelVersion();
        $latestVersion = $this->versionService->latestPanelVersion();

        $result = Result::make()
            ->meta([
                'isLatest' => $isLatest,
                'currentVersion' => $currentVersion,
                'latestVersion' => $latestVersion,
            ])
            ->shortSummary($isLatest ? 'up-to-date' : 'outdated');

        return $isLatest
            ? $result->ok('Panel is up-to-date.')
            : $result->failed('Installed version is `:currentVersion` but latest is `:latestVersion`.');
    }
}
