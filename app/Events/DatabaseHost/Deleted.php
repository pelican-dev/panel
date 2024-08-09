<?php

namespace App\Events\DatabaseHost;

use App\Models\DatabaseHost;
use Illuminate\Queue\SerializesModels;

class Deleted
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public DatabaseHost $databaseHost)
    {
        //
    }
}
