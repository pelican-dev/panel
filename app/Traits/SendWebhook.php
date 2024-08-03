<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Exception;
use GuzzleHttp\Exception\RequestException;

trait SendWebhook
{
    private static function getWebhook($webhook)
    {
        $webhookCategories = [];

        try {
            if (env('WEBHOOK_TYPE') === 'json') {
                $webhookCategories = [
                    'user' => 'USER_WEBHOOK',
                    'egg' => 'EGG_WEBHOOK',
                    'server' => 'SERVER_WEBHOOK',
                    'default' => 'MAIN_WEBHOOK',
                ];
            } elseif (env('WEBHOOK_TYPE') === 'discord') {
                $webhookCategories = [
                    'user' => 'USER_WEBHOOK_DISCORD',
                    'egg' => 'EGG_WEBHOOK_DISCORD',
                    'server' => 'SERVER_WEBHOOK_DISCORD',
                    'default' => 'MAIN_WEBHOOK_DISCORD',
                ];
            } else {
                Log::warning('Unexpected WEBHOOK_TYPE value: ' . env('WEBHOOK_TYPE'));
            }
        } catch (Exception $e) {
            Log::error('Error getWebhook: ' . $e->getMessage());
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
                    $color = env('DISCORD_EMBED_COLOR', '#024cc4');
                    $payload = [
                        'username' => env('APP_NAME'),
                        'avatar_url' => 'https://pelican.dev/img/logo.png',
                        'embeds' => array_map(function ($embed) use ($color) {
                            $embed['color'] = hexdec($color);

                            return $embed;
                        }, $message['embeds'] ?? []),
                    ];

                    $client->post($url, [
                        'json' => $payload,
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                } else {
                    Log::warning('Unexpected WEBHOOK_TYPE value during send: ' . env('WEBHOOK_TYPE'));
                }
            } catch (RequestException $e) {
                Log::error('Guzzle error: ' . $e->getMessage());
            }
        }
    }

    public function buildDiscordDescription(array $settings, array $data, $extra): string
    {
        $descriptionParts = [];

        foreach ($settings as $key => $isEnabled) {
            if ($isEnabled && array_key_exists($key, $data)) {
                $descriptionParts[] = ucwords(str_replace('_', ' ', $key)) . ': ' . $data[$key];
            }
        }
        $descriptionParts[] = $extra;

        return implode("\n", $descriptionParts);
    }
}
