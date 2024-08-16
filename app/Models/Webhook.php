<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $fillable = ['payload', 'successful_at', 'event', 'endpoint'];

    public function casts()
    {
        return [
            'payload' => 'array',
            'successful_at' => 'datetime',
        ];
    }
}
