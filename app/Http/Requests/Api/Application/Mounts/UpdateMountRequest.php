<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Models\Mount;

class UpdateMountRequest extends StoreMountRequest
{
    /**
     * Apply validation rules to this request.
     */
    public function rules(array $rules = null): array
    {
        /** @var Mount $mount */
        $mount = $this->route()->parameter('mount');

        return Mount::getRulesForUpdate($mount->id);
    }
}
