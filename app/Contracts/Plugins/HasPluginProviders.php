<?php

namespace App\Contracts\Plugins;

interface HasPluginProviders
{
    /**
     * @return string[]
     */
    public function getProviders(): array;
}
