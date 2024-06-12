<?php

namespace App\Services\Helpers;

use GuzzleHttp\Client;
use Carbon\CarbonImmutable;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class SoftwareVersionService
{
    public const VERSION_CACHE_KEY = 'panel:versioning_data';

    private static array $result;

    /**
     * SoftwareVersionService constructor.
     */
    public function __construct(
        protected CacheRepository $cache,
        protected Client $client
    ) {
        self::$result = $this->cacheVersionData();
    }

    /**
     * Get the latest version of the panel from the CDN servers.
     */
    public function getPanel(): string
    {
        return Arr::get(self::$result, 'panel') ?? 'error';
    }

    /**
     * Get the latest version of the daemon from the CDN servers.
     */
    public function getDaemon(): string
    {
        return Arr::get(self::$result, 'daemon') ?? 'error';
    }

    /**
     * Get the URL to the discord server.
     */
    public function getDiscord(): string
    {
        return Arr::get(self::$result, 'discord') ?? 'https://pelican.dev/discord';
    }

    /**
     * Get the donation URL.
     */
    public function getDonations(): string
    {
        return Arr::get(self::$result, 'donate') ?? 'https://pelican.dev/donate';
    }

    /**
     * Determine if the current version of the panel is the latest.
     */
    public function isLatestPanel(): bool
    {
        if (config('app.version') === 'canary') {
            return true;
        }

        return version_compare(config('app.version'), $this->getPanel()) >= 0;
    }

    /**
     * Determine if a passed daemon version string is the latest.
     */
    public function isLatestDaemon(string $version): bool
    {
        if ($version === 'develop') {
            return true;
        }

        return version_compare($version, $this->getDaemon()) >= 0;
    }

    /**
     * Keeps the versioning cache up-to-date with the latest results from the CDN.
     */
    protected function cacheVersionData(): array
    {
        return $this->cache->remember(self::VERSION_CACHE_KEY, CarbonImmutable::now()->addMinutes(config('panel.cdn.cache_time', 60)), function () {
            $versionData = [];

            try {
                $response = $this->client->request('GET', 'https://api.github.com/repos/pelican-dev/panel/releases/latest');
                if ($response->getStatusCode() === 200) {
                    $panelData = json_decode($response->getBody(), true);
                    $versionData['panel'] = trim($panelData['tag_name'], 'v');
                }

                $response = $this->client->request('GET', 'https://api.github.com/repos/pelican-dev/wings/releases/latest');
                if ($response->getStatusCode() === 200) {
                    $wingsData = json_decode($response->getBody(), true);
                    $versionData['daemon'] = trim($wingsData['tag_name'], 'v');
                }
            } catch (ClientException $e) {
            }

            $versionData['discord'] = 'https://pelican.dev/discord';
            $versionData['donate'] = 'https://pelican.dev/donate';

            return $versionData;
        });
    }

    public function versionData(): array
    {
        return cache()->remember('git-version', 5, function () {
            if (file_exists(base_path('.git/HEAD'))) {
                $head = explode(' ', file_get_contents(base_path('.git/HEAD')));

                if (array_key_exists(1, $head)) {
                    $path = base_path('.git/' . trim($head[1]));
                }
            }

            if (isset($path) && file_exists($path)) {
                return [
                    'version' => 'canary (' . substr(file_get_contents($path), 0, 8) . ')',
                    'is_git' => true,
                ];
            }

            return [
                'version' => config('app.version'),
                'is_git' => false,
            ];
        });
    }
}
