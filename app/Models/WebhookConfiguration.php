<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookConfiguration extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'endpoint',
        'description',
        'events'
    ];

    protected function casts(): array
    {
        return [
            'events' => 'json',
        ];
    }

    public function findManyByEvent()
    {

    }
}
