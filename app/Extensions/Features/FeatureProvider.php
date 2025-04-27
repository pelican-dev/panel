<?php

namespace App\Extensions\Features;

use Filament\Actions\Action;
use Illuminate\Foundation\Application;

abstract class FeatureProvider
{
    /**
     * @var array<string, static>
     */
    protected static array $providers = [];

    /**
     * @param  string[]  $id
     * @return self|static[]
     */
    public static function getProviders(string|array|null $id = null): array|self
    {
        if (is_array($id)) {
            return array_intersect_key(static::$providers, array_flip($id));
        }

        return $id ? static::$providers[$id] : static::$providers;
    }

    protected function __construct(protected Application $app)
    {
        if (array_key_exists($this->getId(), static::$providers)) {
            if (!$this->app->runningUnitTests()) {
                logger()->warning("Tried to create duplicate Feature provider with id '{$this->getId()}'");
            }

            return;
        }

        static::$providers[$this->getId()] = $this;
    }

    abstract public function getId(): string;

    /**
     * A matching subset string (case-insensitive) from the console output
     *
     * @return array<string>
     */
    abstract public function getListeners(): array;

    abstract public function getAction(): Action;
}
