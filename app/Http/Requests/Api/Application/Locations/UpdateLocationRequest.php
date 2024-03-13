<?php

namespace App\Http\Requests\Api\Application\Locations;

use App\Models\Location;

class UpdateLocationRequest extends StoreLocationRequest
{
    /**
     * Rules to validate this request against.
     */
    public function rules(): array
    {
        $locationId = $this->route()->parameter('location')->id;

        return collect(Location::getRulesForUpdate($locationId))->only([
            'short',
            'long',
        ])->toArray();
    }
}
