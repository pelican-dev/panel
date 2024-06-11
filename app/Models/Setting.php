<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $fillable = ['key', 'label', 'value', 'type', 'attributes', 'description', 'limit', 'group'];

    protected $casts = [
        'attributes' => 'array',
        'limit' => 'integer',
    ];
}
