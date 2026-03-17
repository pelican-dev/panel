<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $node_id
 * @property int $role_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeRole whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NodeRole whereRoleId($value)
 */
class NodeRole extends Pivot
{
    protected $table = 'node_role';

    protected $primaryKey = null;
}
