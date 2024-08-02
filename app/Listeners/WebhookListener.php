<?php

namespace App\Listeners;

use App\Events\User as UserEvents;
use App\Events\Egg as EggEvents;
use App\Traits\SendWebhook;
use Carbon\Carbon;

class WebhookListener
{
    use SendWebhook;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event)
    {
        if ($event instanceof UserEvents\Created) {
            $this->handleUserCreated($event);
        } elseif ($event instanceof UserEvents\Deleted) {
            $this->handleUserDeleted($event);
        } elseif ($event instanceof EggEvents\Created) {
            $this->handleEggCreated($event);
        } elseif ($event instanceof EggEvents\Deleted) {
            $this->handleEggDeleted($event);
        }

    }

    protected function handleUserCreated($event)
    {
        $userId = $event->user->id;
        $username = $event->user->username;
        $email = $event->user->email;
        $appName = env('APP_NAME');
        $appUrl = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $color = hexdec(env('DISCORD_EMBED_COLOR')) ?: 7423;

        if (env('WEBHOOK_TYPE') === 'json') {
            $message = [
                'ID' => $userId,
                'Username' => $username,
                'Email' => $email,
            ];

            $this->send(
                'user',
                [
                    'event' => 'User Created',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'icon_url' => 'https://pelican.dev/img/logo.png',
                        'url' => $appUrl,
                    ],
                    'title' => 'User Created',
                    'description' => "ID: $userId\nUsername: $username\nEmail: $email",
                    'color' => $color,
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('user', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleUserDeleted($event)
    {
        $userId = $event->user->id;
        $username = $event->user->username;
        $email = $event->user->email;
        $appName = env('APP_NAME');
        $appUrl = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $color = hexdec(env('DISCORD_EMBED_COLOR')) ?: 7423;
        $admin = auth()->user()->username;

        if (env('WEBHOOK_TYPE') === 'json') {
            $message = [
                'ID' => $userId,
                'Username' => $username,
                'Email' => $email,
                'Deleted by' => $admin,
            ];

            $this->send(
                'user',
                [
                    'event' => 'User Deleted',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'icon_url' => 'https://pelican.dev/img/logo.png',
                        'url' => $appUrl,
                    ],
                    'title' => 'User Deleted',
                    'description' => "ID: $userId\nUsername: $username\nEmail: $email\n\nDeleted by: $admin",
                    'color' => $color,
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('user', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleEggCreated($event)
    {
        $ID = $event->egg->id;
        $author = $event->egg->author;
        $name = $event->egg->name;
        $description = $event->egg->description;
        $admin = auth()->user()->username;
        $appName = env('APP_NAME');
        $appUrl = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $color = hexdec(env('DISCORD_EMBED_COLOR')) ?: 7423;

        if (env('WEBHOOK_TYPE') === 'json') {
            $message = [
                'id' => $ID,
                'name' => $name,
                'egg author' => $author,
                'description' => $description,
                'added by' => $admin,
            ];

            $this->send(
                'user',
                [
                    'event' => 'Egg Added',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'icon_url' => 'https://pelican.dev/img/logo.png',
                        'url' => $appUrl,
                    ],
                    'title' => 'Egg Added',
                    'description' => "ID: $ID\nName: $name\nEgg Author: $author\nDescription: $description\n\nAdded by: $admin",
                    'color' => $color,
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('egg', [
                'embeds' => $embed,
            ]);
        }
    }

    protected function handleEggDeleted($event)
    {
        $ID = $event->egg->id;
        $author = $event->egg->author;
        $name = $event->egg->name;
        $description = $event->egg->description;
        $admin = auth()->user()->username;
        $appName = env('APP_NAME');
        $appUrl = env('APP_URL');
        $currentTime = Carbon::now()->toDateTimeString();
        $color = hexdec(env('DISCORD_EMBED_COLOR')) ?: 7423;

        if (env('WEBHOOK_TYPE') === 'json') {
            $message = [
                'id' => $ID,
                'name' => $name,
                'egg author' => $author,
                'description' => $description,
                'added by' => $admin,
            ];

            $this->send(
                'user',
                [
                    'event' => 'Egg Added',
                    'triggered_at' => $currentTime,
                    'data' => $message,
                ]
            );
        } elseif (env('WEBHOOK_TYPE') === 'discord') {
            $embed = [
                [
                    'author' => [
                        'name' => $appName,
                        'icon_url' => 'https://pelican.dev/img/logo.png',
                        'url' => $appUrl,
                    ],
                    'title' => 'Egg Added',
                    'description' => "ID: $ID\nName: $name\nEgg Author: $author\nDescription: $description\n\nAdded by: $admin",
                    'color' => $color,
                    'footer' => [
                        'text' => "Current Time: $currentTime",
                    ],
                ],
            ];

            $this->send('egg', [
                'embeds' => $embed,
            ]);
        }
    }
}
