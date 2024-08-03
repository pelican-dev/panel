<?php

namespace App\Events\Node;

use App\Models\Node;
use Illuminate\Queue\SerializesModels;

class Deleted
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Node $node)
    {
        //
    }
}
