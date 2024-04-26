<?php

namespace App\Transformers\Api\Application;

use App\Models\Mount;

class MountTransformer extends BaseTransformer
{
    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return Mount::RESOURCE_NAME;
    }

    public function transform(Mount $model)
    {
        return $model->toArray();
    }
}
