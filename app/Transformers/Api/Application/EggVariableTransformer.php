<?php

namespace App\Transformers\Api\Application;

use App\Models\Egg;
use App\Models\EggVariable;

class EggVariableTransformer extends BaseTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Egg::RESOURCE_NAME;
    }

    /**
     * @param  EggVariable  $model
     */
    public function transform($model): array
    {
        return $model->toArray();
    }
}
