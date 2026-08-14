<?php

namespace App\Http\Requests\Api\Application\Mounts;

class UpdateMountEggsRequest extends StoreMountRequest
{
    /**
     * @param  array<string, string|string[]>|null  $rules
     * @return array<string, string|string[]>
     */
    public function rules(?array $rules = null): array
    {
        return [
            /** IDs of the eggs allowed to use this mount. Eggs left out of this list are unassigned. */
            'eggs' => 'required|array|exists:eggs,id',
            /** ID of an egg allowed to use this mount. */
            'eggs.*' => 'integer',
        ];
    }
}
