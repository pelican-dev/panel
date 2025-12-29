<?php

namespace App\Console\Commands\Overrides;

use Illuminate\Foundation\Console\OptimizeCommand as BaseOptimizeCommand;

class OptimizeCommand extends BaseOptimizeCommand
{
    /**
     * Prevent config from being cached
     *
     * @return array<string, string>
     */
    protected function getOptimizeTasks()
    {
        return array_except(parent::getOptimizeTasks(), 'config');
    }
}
