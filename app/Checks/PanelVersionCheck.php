<?php

namespace App\Checks;

use App\Services\Helpers\SoftwareVersionService;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class PanelVersionCheck extends Check
{
    public function run(): Result
    {
        /** @var SoftwareVersionServivce $versionService */
        $versionService = app(SoftwareVersionService::class);

        $isLatest = $versionService->isLatestPanel();
        $currentVersion = $versionService->versionData()['version'];
        $latestVersion = $versionService->getPanel();

        $result = Result::make()
            ->meta([
                'isLatest' => $isLatest,
                'currentVersion' => $currentVersion,
                'latestVersion' => $latestVersion,
            ])
            ->shortSummary($isLatest);

        return $isLatest
            ? $result->ok('Panel is up-to-date')
            : $result->failed("Installed version is `{$currentVersion}` but latest is `{$latestVersion}`");
    }
}
