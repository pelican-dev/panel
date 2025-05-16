<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NodeRole extends Pivot
{
    protected $table = 'node_role';

    protected $primaryKey = null;
}
