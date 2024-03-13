<?php

namespace App\Http\Requests\Api\Application\Nests\Eggs;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetEggRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_EGGS;

    protected int $permission = AdminAcl::READ;
}
