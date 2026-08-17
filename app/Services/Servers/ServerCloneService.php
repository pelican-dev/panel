<?php

namespace App\Services\Servers;

use App\Models\EggVariable;
use App\Models\Mount;
use App\Models\Server;

class ServerCloneService
{
    /**
     * Collect the configuration of an existing server so it can be used to pre-fill
     * the server creation form. Everything that has to be unique for a server, like
     * its allocations or its external id, is intentionally left out.
     *
     * @return array<string, mixed>
     */
    public function handle(Server $server): array
    {
        $egg = $server->egg;

        $variables = $server->variables
            ->sortBy('sort')
            ->map(fn (EggVariable $variable) => [
                ...$variable->toArray(),
                'variable_id' => $variable->id,
                'variable_value' => $variable->server_value ?? $variable->default_value ?? '',
            ])
            ->values()
            ->all();

        return [
            'name' => str(trans('admin/server.clone_name', ['name' => $server->name]))->limit(255, '')->toString(),
            'description' => $server->description,
            'external_id' => null,
            'node_id' => $server->node_id,
            'owner_id' => $server->owner_id,
            'allocation_id' => null,
            'allocation_additional' => [],
            'egg_id' => $server->egg_id,
            'skip_scripts' => (int) $server->skip_scripts,
            'start_on_completion' => 1,
            'startup' => $server->startup,
            'select_startup' => in_array($server->startup, $egg->startup_commands ?? []) ? $server->startup : 'custom',
            'image' => $server->image,
            'select_image' => in_array($server->image, $egg->docker_images ?? []) ? $server->image : 'ghcr.io/custom-image',
            'docker_labels' => $server->docker_labels ?? [],
            'unlimited_cpu' => (int) !$server->cpu,
            'cpu' => $server->cpu ?? 0,
            'unlimited_mem' => (int) !$server->memory,
            'memory' => $server->memory ?? 0,
            'unlimited_disk' => (int) !$server->disk,
            'disk' => $server->disk ?? 0,
            'io' => $server->io,
            'cpu_pinning' => (int) filled($server->threads),
            'threads' => $server->threads,
            'swap_support' => match (true) {
                $server->swap > 0 => 'limited',
                $server->swap < 0 => 'unlimited',
                default => 'disabled',
            },
            'swap' => $server->swap ?? 0,
            'oom_killer' => (int) $server->oom_killer,
            'allocation_limit' => $server->allocation_limit ?? 0,
            'database_limit' => $server->database_limit ?? 0,
            'backup_limit' => $server->backup_limit ?? 0,
            'mounts' => $server->mounts->map(fn (Mount $mount) => $mount->id)->all(),
            'server_variables' => $variables,
            'environment' => collect($variables)->mapWithKeys(fn (array $variable) => [$variable['env_variable'] => $variable['variable_value']])->all(),
        ];
    }
}
