<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Models\Mount;

class UpdateMountRequest extends StoreMountRequest
{
    /**
     * Apply validation rules to this request. Uses the parent class rules()
     * function but passes in the rules for updating rather than creating.
     */
    public function rules(array $rules = null): array
    {
        /** @var Mount $mount */
        $mount = $this->route()->parameter('mount');

        return parent::rules(Mount::getRulesForUpdate($mount->id));
    }
}
