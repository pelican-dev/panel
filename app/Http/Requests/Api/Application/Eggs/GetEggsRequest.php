<?php

namespace App\Http\Requests\Api\Application\Eggs;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Egg;
use App\Services\Acl\Api\AdminAcl;

class GetEggsRequest extends ApplicationApiRequest
{
    protected ?string $resource = Egg::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
