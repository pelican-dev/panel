<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Models\Mount;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class DeleteMountRequest extends ApplicationApiRequest
{
    protected ?string $resource = Mount::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
