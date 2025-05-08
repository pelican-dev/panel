<?php

namespace App\Extensions\Features;

use App\Models\Egg;
use Illuminate\Database\Eloquent\Model;

class FeatureProvider
{
    /** @var FeatureSchemaInterface[] */
    private array $providers = [];

    /** @return FeatureSchemaInterface[] | FeatureSchemaInterface */
    public function get(?string $id = null): array|FeatureSchemaInterface
    {
        return $id ? $this->providers[$id] : $this->providers;
    }

    /** @return FeatureSchemaInterface[] */
    public function getAvailableFeatures(Egg $egg): array
    {
        return collect($this->providers)->intersect($egg->features)->all();
    }

    public function register(FeatureSchemaInterface $provider): void
    {
        if (array_key_exists($provider->getId(), $this->providers)) {
            return;
        }

        $this->providers[$provider->getId()] = $provider;
    }
}
