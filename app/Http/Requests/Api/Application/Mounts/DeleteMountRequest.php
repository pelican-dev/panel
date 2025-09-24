<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Mount;
use App\Services\Acl\Api\AdminAcl;

class DeleteMountRequest extends ApplicationApiRequest
{
    protected ?string $resource = Mount::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
