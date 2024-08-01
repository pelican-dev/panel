<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

trait SendWebhook
{
    private static function getWebhook($webhook)
    {
        if (env('WEBHOOK_TYPE') === 'json') {
            $webhookCategories = [
                'user' => 'USER_WEBHOOK',
                'default' => 'MAIN_WEBHOOK',
            ];
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $webhookCategories = [
                'user' => 'USER_WEBHOOK_DISCORD',
                'default' => 'MAIN_WEBHOOK_DISCORD',
            ];
        } else {
            $webhookCategories = [
                'user' => 'USER_WEBHOOK',
                'default' => 'MAIN_WEBHOOK',
            ];
        }

        $settingKey = $webhookCategories[$webhook] ?? $webhookCategories['default'];
        $url = env($settingKey);

        if (empty($url)) {
            return env($webhookCategories['default']);
        } else {
            return $url;
        }
    }

    public function send($webhook, $message)
    {
        if (env('WEBHOOKS_ENABLED')) {
            $url = self::getWebhook($webhook);

            $client = new Client();

            try {
                if (env('WEBHOOK_TYPE') === 'json') {
                    $client->post($url, [
                        'json' => $message,
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                } elseif (env('WEBHOOK_TYPE') === 'discord') {
                    $payload = [
                        'username' => env('APP_NAME'),
                        'avatar_url' => 'https://raw.githubusercontent.com/pelican-dev/panel/main/public/pelican.svg',
                        'embeds' => $message['embeds'] ?? [],
                    ];

                    $client->post($url, [
                        'json' => $payload,
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                }
            } catch (RequestException $e) {
                Log::error('Guzzle error: ' . $e->getMessage());
            }
        }
    }
}
