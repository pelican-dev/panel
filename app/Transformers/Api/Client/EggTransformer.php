<?php

namespace App\Transformers\Api\Client;

use App\Models\Egg;

class EggTransformer extends BaseClientTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Egg::RESOURCE_NAME;
    }

    /**
     * @param  Egg  $egg
     */
    public function transform($egg): array
    {
        return [
            'uuid' => $egg->uuid,
            'name' => $egg->name,
        ];
    }
}
