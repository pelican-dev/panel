<?php

namespace App\Http\Requests\Api\Application\Eggs;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Services\Acl\Api\AdminAcl;
use App\Models\Egg;

class GetEggsRequest extends ApplicationApiRequest
{
    protected ?string $resource = Egg::RESOURCE_NAME;

    protected int $permission = AdminAcl::READ;
}
