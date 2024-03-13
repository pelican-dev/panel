<?php

namespace App\Http\Requests\Api\Application\Locations;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;

class GetLocationsRequest extends ApplicationApiRequest
{
    protected ?string $resource = AdminAcl::RESOURCE_LOCATIONS;

    protected int $permission = AdminAcl::READ;
}
