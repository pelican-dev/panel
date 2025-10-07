<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Transformers\Api\Client\BaseClientTransformer;
use Webmozart\Assert\Assert;

abstract class ClientApiController extends ApplicationApiController
{
    /**
     * Returns only the includes which are valid for the given transformer.
     *
     * @param  array<mixed>  $merge
     * @return array<array-key, mixed>
     */
    protected function getIncludesForTransformer(BaseClientTransformer $transformer, array $merge = []): array
    {
        $filtered = array_filter($this->parseIncludes(), function ($datum) use ($transformer) {
            return in_array($datum, $transformer->getAvailableIncludes());
        });

        return array_merge($filtered, $merge);
    }

    /**
     * Returns the parsed includes for this request.
     *
     * @return array<array-key, mixed>
     */
    protected function parseIncludes(): array
    {
        $includes = $this->request->query('include') ?? [];

        if (!is_string($includes)) {
            return $includes;
        }

        return array_map(function ($item) {
            return trim($item);
        }, explode(',', $includes));
    }

    /**
     * Return an instance of an application transformer.
     *
     * @template T of \App\Transformers\Api\Client\BaseClientTransformer
     *
     * @param  class-string<T>  $abstract
     * @return T
     *
     * @noinspection PhpDocSignatureInspection
     */
    public function getTransformer(string $abstract)
    {
        Assert::subclassOf($abstract, BaseClientTransformer::class);

        return $abstract::fromRequest($this->request);
    }
}
