<?php

namespace App\Http\Requests\Api\Application\Servers\Databases;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetServerDatabasesRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_SERVER_DATABASES;

    protected int $permission = AdminAcl::READ;
}
