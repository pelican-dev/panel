<?php

namespace App\Extensions\Features;

class FeatureProvider
{
    /** @var FeatureSchemaInterface[] */
    private array $providers = [];

    /**
     * @param  string[]|string|null  $id
     * @return FeatureSchemaInterface[] | FeatureSchemaInterface
     */
    public function get(array|string|null $id = null): array|FeatureSchemaInterface
    {
        if (is_array($id)) {
            return collect($this->providers)->only($id)->all();
        }

        return $id ? $this->providers[$id] : $this->providers;
    }

    public function register(FeatureSchemaInterface $provider): void
    {
        if (array_key_exists($provider->getId(), $this->providers)) {
            return;
        }

        $this->providers[$provider->getId()] = $provider;
    }
}
