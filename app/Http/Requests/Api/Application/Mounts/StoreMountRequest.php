<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Mount;

class StoreMountRequest extends ApplicationApiRequest
{
    protected ?string $resource = Mount::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
