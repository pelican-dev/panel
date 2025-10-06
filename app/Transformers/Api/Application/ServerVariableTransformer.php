<?php

namespace App\Transformers\Api\Application;

use App\Models\Egg;
use App\Models\EggVariable;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\NullResource;

class ServerVariableTransformer extends BaseTransformer
{
    /**
     * List of resources that can be included.
     */
    protected array $availableIncludes = ['parent'];

    /**
     * Return the resource name for the JSONAPI output.
     */
    public function getResourceName(): string
    {
        return EggVariable::RESOURCE_NAME;
    }

    /**
     * @param  EggVariable  $variable
     */
    public function transform($variable): array
    {
        return $variable->toArray();
    }

    /**
     * Return the parent service variable data.
     */
    public function includeParent(EggVariable $variable): Item|NullResource
    {
        if (!$this->authorize(Egg::RESOURCE_NAME)) {
            return $this->null();
        }

        $variable->loadMissing('variable');

        return $this->item($variable->getRelation('variable'), $this->makeTransformer(EggVariableTransformer::class), 'variable');
    }
}
