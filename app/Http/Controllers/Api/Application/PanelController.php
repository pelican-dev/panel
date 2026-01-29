<?php

namespace App\Http\Controllers\Api\Application;

use App\Http\Requests\Api\Application\GetPanelInfoRequest;
use App\Transformers\Api\Application\PanelInfoTransformer;

class PanelController extends ApplicationApiController
{
    /**
     * Get panel statistics
     *
     * Returns panel information
     *
     * @return array<mixed>
     */
    public function __invoke(GetPanelInfoRequest $request): array
    {
        return $this->fractal->item(null)
            ->transformWith($this->getTransformer(PanelInfoTransformer::class))
            ->toArray();
    }
}
