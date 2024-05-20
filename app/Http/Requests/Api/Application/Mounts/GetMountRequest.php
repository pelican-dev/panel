<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetMountRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_MOUNTS;

    protected int $permission = AdminAcl::READ;
}
