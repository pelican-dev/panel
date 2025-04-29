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

    public function viewAny(): string
    {
        return RolePermissionPrefixes::ViewAny->value . ' ' . $this->value;
    }

    public function view(): string
    {
        return RolePermissionPrefixes::View->value . ' ' . $this->value;
    }

    public function create(): string
    {
        return RolePermissionPrefixes::Create->value . ' ' . $this->value;
    }

    public function update(): string
    {
        return RolePermissionPrefixes::Update->value . ' ' . $this->value;
    }
}
