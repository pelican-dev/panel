<?php

namespace App\Services\Servers\Sharing;

use App\Models\Server;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Yaml\Yaml;

class ServerConfigExporterService
{
    /**
     * @param  array<string, bool>  $options
     */
    public function handle(Server|int $server, array $options = []): string
    {
        if (!$server instanceof Server) {
            $server = Server::with(['egg', 'allocations', 'serverVariables.variable'])->findOrFail($server);
        }

        $includeDescription = $options['include_description'] ?? true;
        $includeAllocations = $options['include_allocations'] ?? true;
        $includeVariableValues = $options['include_variable_values'] ?? true;

        $data = [
            'name' => $server->name,
            'egg' => [
                'uuid' => $server->egg->uuid,
                'name' => $server->egg->name,
            ],
            'settings' => [
                'startup' => $server->startup,
                'image' => $server->image,
                'skip_scripts' => $server->skip_scripts,
            ],
            'limits' => [
                'memory' => $server->memory,
                'swap' => $server->swap,
                'disk' => $server->disk,
                'io' => $server->io,
                'cpu' => $server->cpu,
                'threads' => $server->threads,
                'oom_killer' => $server->oom_killer,
            ],
            'feature_limits' => [
                'databases' => $server->database_limit,
                'allocations' => $server->allocation_limit,
                'backups' => $server->backup_limit,
            ],
        ];

        if ($includeDescription && !empty($server->description)) {
            $data['description'] = $server->description;
        }

        // Export server icon if exists
        $iconData = $this->exportServerIcon($server);
        if ($iconData) {
            $data['icon'] = $iconData;
        }

        if ($includeAllocations && $server->allocations->isNotEmpty()) {
            $data['allocations'] = $server->allocations->map(function ($allocation) use ($server) {
                return [
                    'ip' => $allocation->ip,
                    'port' => $allocation->port,
                    'is_primary' => $allocation->id === $server->allocation_id,
                ];
            })->values()->all();
        }

        if ($includeVariableValues && $server->serverVariables->isNotEmpty()) {
            $data['variables'] = $server->serverVariables->map(function ($serverVar) {
                return [
                    'env_variable' => $serverVar->variable->env_variable,
                    'value' => $serverVar->variable_value,
                ];
            })->values()->all();
        }

        return Yaml::dump($data, 4, 2);
    }

    /**
     * Export server icon as base64 encoded string with mime type.
     *
     * @return array<string, string>|null
     */
    protected function exportServerIcon(Server $server): ?array
    {
        foreach (array_keys(Server::IMAGE_FORMATS) as $ext) {
            $path = Server::ICON_STORAGE_PATH . "/{$server->uuid}.{$ext}";
            if (Storage::disk('public')->exists($path)) {
                $contents = Storage::disk('public')->get($path);
                $mimeType = Server::IMAGE_FORMATS[$ext];

                return [
                    'data' => base64_encode($contents),
                    'mime_type' => $mimeType,
                    'extension' => $ext,
                ];
            }
        }

        return null;
    }
}
