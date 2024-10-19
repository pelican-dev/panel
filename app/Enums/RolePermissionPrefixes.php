<?php

namespace App\Enums;

enum RolePermissionPrefixes: string
{
    case ViewAny = 'viewList';
    case View = 'view';
    case Create = 'create';
    case Update = 'update';
    case Delete = 'delete';
}
