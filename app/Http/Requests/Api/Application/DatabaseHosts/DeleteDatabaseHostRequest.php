<?php

namespace App\Http\Requests\Api\Application\DatabaseHosts;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\DatabaseHost;
use App\Services\Acl\Api\AdminAcl;

class DeleteDatabaseHostRequest extends ApplicationApiRequest
{
    protected ?string $resource = DatabaseHost::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
