<?php

namespace App\Http\Requests\Api\Application;

use App\Services\Acl\Api\AdminAcl;

class GetPanelInfoRequest extends ApplicationApiRequest
{
    protected ?string $resource = 'panel';

    protected int $permission = AdminAcl::READ;

    public function authorize(): bool
    {
        // Any valid application API key can access panel information
        return $this->user() !== null;
    }
}
