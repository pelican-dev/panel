<?php

namespace App\Http\Requests\Api\Application\Servers;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Server;
use App\Services\Acl\Api\AdminAcl;

class ServerWriteRequest extends ApplicationApiRequest
{
    protected ?string $resource = Server::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
