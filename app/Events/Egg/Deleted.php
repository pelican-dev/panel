<?php

namespace App\Events\Egg;

use App\Models\Egg;
use Illuminate\Queue\SerializesModels;

class Deleted
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Egg $egg)
    {
        //
    }
}
