<?php

namespace App\Http\Requests\Api\Application\Servers\Databases;

use App\Models\Database;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetServerDatabasesRequest extends ApplicationApiRequest
{
    protected ?string $resource = Database::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
