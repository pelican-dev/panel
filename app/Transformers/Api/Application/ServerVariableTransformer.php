<?php

namespace App\Transformers\Api\Application;

use League\Fractal\Resource\Item;
use App\Models\EggVariable;
use App\Models\Egg;
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
     * Return a generic transformed server variable array.
     */
    public function transform(EggVariable $variable): array
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
