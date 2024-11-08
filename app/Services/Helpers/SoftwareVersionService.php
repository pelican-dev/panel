<?php

namespace App\Services\Helpers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

class SoftwareVersionService
{
    public function latestPanelVersion(): string
    {
        $data = $this->getVersionData();

        return $data['panel'] ?? 'error';
    }

    public function latestWingsVersion(): string
    {
        $data = $this->getVersionData();

        return $data['wings'] ?? 'error';
    }

    public function isLatestPanel(): bool
    {
        if (config('app.version') === 'canary') {
            return true;
        }

        return version_compare(config('app.version'), $this->latestPanelVersion()) >= 0;
    }

    public function isLatestWings(string $version): bool
    {
        if ($version === 'develop') {
            return true;
        }

        return version_compare($version, $this->latestWingsVersion()) >= 0;
    }

    public function currentPanelVersion(): array
    {
        return cache()->remember('panel:git-version', now()->addMinutes(5), function () {
            if (file_exists(base_path('.git/HEAD'))) {
                $head = explode(' ', file_get_contents(base_path('.git/HEAD')));

                if (array_key_exists(1, $head)) {
                    $path = base_path('.git/' . trim($head[1]));

                    if (file_exists($path)) {
                        return 'canary (' . substr(file_get_contents($path), 0, 7) . ')';
                    }
                }
            }

            return config('app.version');
        });
    }

    private function getVersionData(): array
    {
        return cache()->remember('panel:version_data', now()->addMinutes(config('panel.cdn.cache_time', 60)), function () {
            $versionData = [];

            try {
                $response = Http::timeout(5)->connectTimeout(1)->get('https://api.github.com/repos/pelican-dev/panel/releases/latest')->throw()->json();
                $versionData['panel'] = trim($response['tag_name'], 'v');

                $response = Http::timeout(5)->connectTimeout(1)->get('https://api.github.com/repos/pelican-dev/wings/releases/latest')->throw()->json();
                $versionData['wings'] = trim($response['tag_name'], 'v');
            } catch (GuzzleException) {
            }

            return $versionData;
        });
    }
}
