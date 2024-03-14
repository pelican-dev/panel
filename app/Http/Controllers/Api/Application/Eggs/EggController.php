<?php

namespace App\Http\Controllers\Api\Application\Eggs;

use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Eggs\GetEggRequest;
use App\Http\Requests\Api\Application\Eggs\GetEggsRequest;
use App\Models\Egg;
use App\Transformers\Api\Application\EggTransformer;

class EggController extends ApplicationApiController
{
    /**
     * Return all eggs
     */
    public function index(GetEggsRequest $request): array
    {
        return $this->fractal->collection(Egg::all())
            ->transformWith($this->getTransformer(EggTransformer::class))
            ->toArray();
    }

    /**
     * Return a single egg that exists
     */
    public function view(GetEggRequest $request, Egg $egg): array
    {
        return $this->fractal->item($egg)
            ->transformWith($this->getTransformer(EggTransformer::class))
            ->toArray();
    }
}
