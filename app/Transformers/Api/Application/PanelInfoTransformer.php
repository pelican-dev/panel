<?php

namespace App\Transformers\Api\Application;

use App\Services\Helpers\SoftwareVersionService;

class PanelInfoTransformer extends BaseTransformer
{
    private SoftwareVersionService $versionService;

    public function handle(SoftwareVersionService $versionService): void
    {
        $this->versionService = $versionService;
    }

    public function getResourceName(): string
    {
        return 'panel';
    }

    /**
     * @param  null  $model
     */
    public function transform($model): array
    {
        $currentVersion = config('app.version', 'canary');

        return [
            'version' => $currentVersion,
            'git_hash' => $this->getGitHash(),
            'fqdn' => parse_url(config('app.url'), PHP_URL_HOST),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'latest_version' => $this->versionService->latestPanelVersion(),
            'is_latest' => $this->versionService->isLatestPanel(),
        ];
    }

    private function getGitHash(): ?string
    {
        if (file_exists(base_path('.git/HEAD'))) {
            $head = explode(' ', file_get_contents(base_path('.git/HEAD')));
            if (array_key_exists(1, $head)) {
                $path = base_path('.git/' . trim($head[1]));
                if (file_exists($path)) {
                    return substr(file_get_contents($path), 0, 7);
                }
            }
        }

        return null;
    }
}
