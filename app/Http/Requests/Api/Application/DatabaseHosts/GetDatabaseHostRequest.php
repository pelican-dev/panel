<?php

namespace App\Http\Requests\Api\Application\DatabaseHosts;

use App\Models\DatabaseHost;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetDatabaseHostRequest extends ApplicationApiRequest
{
    protected ?string $resource = DatabaseHost::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
