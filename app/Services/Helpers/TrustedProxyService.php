<?php

namespace App\Services\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TrustedProxyService
{
    /**
     * TrustedProxyService constructor.
     */
    public function __construct(
        protected Client $client
    ) {
    }

    /**
     * Get provider ips list from given url
     */
    public function handle(): array
    {
        $ips = collect();
        try {
            $response = $this->client->request(
                'GET',
                config('trustedproxy.auto.url'),
                config('panel.guzzle')
            );
            if ($response->getStatusCode() === 200) {
                $result = json_decode($response->getBody(), true);
                foreach (config('trustedproxy.auto.keys') as $value) {
                    $ips->push(...data_get($result, $value));
                }
                $ips->unique();
            }
        } catch (GuzzleException $e) {
        }

        return $ips->values()->all();
    }
}
