<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PwaPushSubscription extends Model
{
    protected $table = 'pwa_push_subscriptions';

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'endpoint',
        'public_key',
        'auth_token',
        'content_encoding',
        'user_agent',
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}
