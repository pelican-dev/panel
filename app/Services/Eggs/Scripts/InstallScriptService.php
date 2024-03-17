<?php

namespace App\Services\Eggs\Scripts;

use App\Models\Egg;

class InstallScriptService
{
    /**
     * Modify the install script for a given Egg.
     */
    public function handle(Egg $egg, array $data): void
    {
        $egg->update([
            'script_install' => array_get($data, 'script_install'),
            'script_is_privileged' => array_get($data, 'script_is_privileged', 1),
            'script_entry' => array_get($data, 'script_entry'),
            'script_container' => array_get($data, 'script_container'),
            'copy_script_from' => array_get($data, 'copy_script_from'),
        ]);
    }
}
