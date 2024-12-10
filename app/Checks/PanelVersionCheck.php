<?php

namespace App\Checks;

use App\Services\Helpers\SoftwareVersionService;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class PanelVersionCheck extends Check
{
    public function run(): Result
    {
        /** @var SoftwareVersionService $versionService */
        $versionService = app(SoftwareVersionService::class); // @phpstan-ignore-line

        $isLatest = $versionService->isLatestPanel();
        $currentVersion = $versionService->currentPanelVersion();
        $latestVersion = $versionService->latestPanelVersion();

        $result = Result::make()
            ->meta([
                'isLatest' => $isLatest,
                'currentVersion' => $currentVersion,
                'latestVersion' => $latestVersion,
            ])
            ->shortSummary($isLatest ? 'up-to-date' : 'outdated');

        return $isLatest
            ? $result->ok('Panel is up-to-date')
            : $result->failed("Installed version is `{$currentVersion}` but latest is `{$latestVersion}`");
    }
}
