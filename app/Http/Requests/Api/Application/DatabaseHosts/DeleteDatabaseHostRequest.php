<?php

namespace App\Http\Requests\Api\Application\DatabaseHosts;

use App\Services\Acl\Api\AdminAcl;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\DatabaseHost;

class DeleteDatabaseHostRequest extends ApplicationApiRequest
{
    protected ?string $resource = DatabaseHost::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
