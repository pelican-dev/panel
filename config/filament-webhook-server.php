<?php

use Marjose123\FilamentWebhookServer\Pages\WebhookHistory;
use Marjose123\FilamentWebhookServer\Pages\Webhooks;

return [
    /*
     *  Models that you want to be part of the webhooks options
    */
    'models' => [
        \App\Models\User::class,
        \App\Models\Egg::class,
        \App\Models\Node::class,
        \App\Models\Server::class,
        \App\Models\DatabaseHost::class,
        \App\Models\ApiKey::class,
        \App\Models\Mount::class,
    ],
    'polling' => '20m',
    'webhook' => [
        'keep_history' => true,
    ],
    'pages' => [
        Webhooks::class,
        WebhookHistory::class,
    ],
    'navigation' => [
        'icon' => 'tabler-webhook',
    ],
];
