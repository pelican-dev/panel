<?php

namespace App\Http\Requests\Api\Application\Eggs;

use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Egg;
use App\Services\Acl\Api\AdminAcl;

class ImportEggRequest extends ApplicationApiRequest
{
    protected ?string $resource = Egg::RESOURCE_NAME;

    protected int $permission = AdminAcl::WRITE;

    public function rules(): array
    {
        return [
            'format' => 'nullable|string|in:yaml,json',
        ];
    }
}
