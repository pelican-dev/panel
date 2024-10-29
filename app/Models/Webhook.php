<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory, MassPrunable;

    protected $fillable = ['payload', 'successful_at', 'event', 'endpoint'];

    public function casts()
    {
        return [
            'payload' => 'array',
            'successful_at' => 'datetime',
        ];
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', Carbon::now()->subDays(config('panel.webhook.prune_days')));
    }
}
