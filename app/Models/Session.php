<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sessions';

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'user_id' => 'integer',
        ];
    }
}
