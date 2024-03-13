<?php

namespace App\Http\Requests\Api\Application\Servers;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetExternalServerRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_SERVERS;

    protected int $permission = AdminAcl::READ;
}
