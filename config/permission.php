<?php

use App\Models\Role;
use Spatie\Permission\Models\Permission;

return [

    'models' => [

        'permission' => Permission::class,

        'role' => Role::class,

    ],

];
