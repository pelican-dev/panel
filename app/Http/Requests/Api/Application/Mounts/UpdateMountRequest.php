<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Models\Mount;

class UpdateMountRequest extends StoreMountRequest
{
    /**
     * @param array<string, string|array<string>>|null $rules
     * @return array<string, string|array<string>>
     */

    public function rules(?array $rules = null): array
    {
        /** @var Mount $mount */
        $mount = $this->route()->parameter('mount');

        return Mount::getRulesForUpdate($mount);
    }
}
