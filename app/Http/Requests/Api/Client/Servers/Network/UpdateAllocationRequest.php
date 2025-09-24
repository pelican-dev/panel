<?php

namespace App\Http\Requests\Api\Client\Servers\Network;

use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Allocation;
use App\Models\Permission;

class UpdateAllocationRequest extends ClientApiRequest
{
    public function permission(): string
    {
        return Permission::ACTION_ALLOCATION_UPDATE;
    }

    public function rules(): array
    {
        $rules = Allocation::getRules();

        return [
            'notes' => array_merge($rules['notes'], ['present']),
        ];
    }
}
