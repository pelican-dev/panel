<?php

namespace App\Contracts\Plugins;

interface HasPluginCommands
{
    /**
     * @return string[]
     */
    public function getCommands(): array;
}
