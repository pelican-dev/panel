<?php

namespace App\Enums;

enum RolePermissionModels: string
{
    case ApiKey = 'apiKey';
    case DatabaseHost = 'databaseHost';
    case Database = 'database';
    case Egg = 'egg';
    case Mount = 'mount';
    case Node = 'node';
    case Role = 'role';
    case Server = 'server';
    case User = 'user';
    case Webhook = 'webhook';
}
