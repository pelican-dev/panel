<?php

namespace App\Services\Eggs;

use App\Models\Egg;
use App\Models\Server;
use App\Models\ServerVariable;

class EggChangerService
{
    public function handle(Server $server, Egg $newEgg): void
    {
        // Change egg id, default image and startup command
        $server->forceFill([
            'egg_id' => $newEgg->id,
            'image' => array_values($newEgg->docker_images)[0],
            'startup' => $newEgg->startup,
        ])->saveOrFail();

        // Keep copy of old server variables
        $oldVariables = [];
        foreach ($server->serverVariables as $serverVariable) {
            $oldVariables[$serverVariable->variable->env_variable] = $serverVariable->variable_value;
        }

        // Delete old server variables
        ServerVariable::where('server_id', $server->id)->delete();

        // Create new server variables
        foreach ($newEgg->variables as $eggVariable) {
            ServerVariable::create([
                'server_id' => $server->id,
                'variable_id' => $eggVariable->id,
                'variable_value' => $oldVariables[$eggVariable->env_variable] ?? $eggVariable->default_value,
            ]);
        }
    }
}
