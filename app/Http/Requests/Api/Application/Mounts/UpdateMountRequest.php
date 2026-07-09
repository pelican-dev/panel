<?php

namespace App\Http\Requests\Api\Application\Mounts;

use App\Models\Mount;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateMountRequest extends StoreMountRequest
{
    /**
     * @param  array<string, string|array<string|\Stringable|ValidationRule>>|null  $rules
     * @return array<string, string|array<string|\Stringable|ValidationRule>>
     */
    public function rules(?array $rules = null): array
    {
        /** @var Mount $mount */
        $mount = $this->route()->parameter('mount');

        return Mount::getRulesForUpdate($mount);
    }
}
