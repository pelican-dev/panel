<?php

namespace App\Http\Requests\Api\Application\Plugins;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Plugin;
use App\Services\Acl\Api\AdminAcl;

class WritePluginRequest extends ApplicationApiRequest
{
    protected ?string $resource = Plugin::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;
}
