<?php

namespace App\Http\Requests\Api\Application\Eggs;

use App\Models\Egg;
use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetEggRequest extends ApplicationApiRequest
{
    protected ?string $resource = Egg::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
