<?php

namespace App\Enums;

enum RolePermissionModels: string
{
    case ApiKey = 'apikey';
    case DatabaseHost = 'databasehost';
    case Database = 'database';
    case Egg = 'egg';
    case Mount = 'mount';
    case Node = 'node';
    case Role = 'role';
    case Server = 'server';
    case User = 'user';
}
