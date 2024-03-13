<?php

namespace App\Http\Requests\Api\Application\Servers\Databases;

use App\Services\Acl\Api\AdminAcl;

class ServerDatabaseWriteRequest extends GetServerDatabasesRequest
{
    protected int $permission = AdminAcl::WRITE;
}
