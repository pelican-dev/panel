<?php

namespace App\Services\Servers\Sharing;

use App\Exceptions\Service\InvalidFileUploadException;
use App\Models\Allocation;
use App\Models\Egg;
use App\Models\EggVariable;
use App\Models\Node;
use App\Models\Server;
use App\Models\ServerVariable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

class ServerConfigCreatorService
{
    /**
     * @throws InvalidFileUploadException
     */
    public function fromFile(UploadedFile $file, ?int $nodeId = null): Server
    {
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new InvalidFileUploadException(trans('admin/server.import_errors.file_error'));
        }

        try {
            $parsed = Yaml::parse($file->getContent());
        } catch (\Exception $exception) {
            throw new InvalidFileUploadException(trans('admin/server.import_errors.parse_error_desc', ['error' => $exception->getMessage()]));
        }

        return $this->createServer($parsed, $nodeId);
    }

    /**
     * Create a server from configuration array.
     *
     * @param  array<string, mixed>  $config
     *
     * @throws InvalidFileUploadException
     */
    protected function createServer(array $config, ?int $nodeId = null): Server
    {
        $eggUuid = Arr::get($config, 'egg.uuid');
        $eggName = Arr::get($config, 'egg.name');

        if (!$eggUuid) {
            throw new InvalidFileUploadException(trans('admin/server.import_errors.egg_uuid_required'));
        }

        $egg = Egg::where('uuid', $eggUuid)->first();

        if (!$egg) {
            throw new InvalidFileUploadException(
                trans('admin/server.import_errors.egg_not_found_desc', [
                    'uuid' => $eggUuid,
                    'name' => $eggName ?: trans('admin/server.none'),
                ])
            );
        }

        if ($nodeId) {
            $node = Node::whereIn('id', user()?->accessibleNodes()->pluck('id'))
                ->where('id', $nodeId)
                ->first();

            if (!$node) {
                throw new InvalidFileUploadException(trans('admin/server.import_errors.node_not_accessible'));
            }
        } else {
            $node = Node::whereIn('id', user()?->accessibleNodes()->pluck('id'))->first();

            if (!$node) {
                throw new InvalidFileUploadException(trans('admin/server.import_errors.no_nodes'));
            }
        }

        $allocations = Arr::get($config, 'allocations', []);
        $primaryAllocation = null;
        $createdAllocations = [];

        if (!empty($allocations)) {
            foreach ($allocations as $allocationData) {
                $ip = Arr::get($allocationData, 'ip');
                $port = Arr::get($allocationData, 'port');
                $isPrimary = Arr::get($allocationData, 'is_primary', false);

                $allocation = Allocation::where('node_id', $node->id)
                    ->where('ip', $ip)
                    ->where('port', $port)
                    ->whereNull('server_id')
                    ->first();

                if (!$allocation) {
                    $existingAllocation = Allocation::where('node_id', $node->id)
                        ->where('ip', $ip)
                        ->where('port', $port)
                        ->first();

                    if ($existingAllocation) {
                        $port = $this->findNextAvailablePort($node->id, $ip, $port);
                    }

                    $allocation = Allocation::create([
                        'node_id' => $node->id,
                        'ip' => $ip,
                        'port' => $port,
                    ]);
                }

                $createdAllocations[] = $allocation;

                if ($isPrimary && !$primaryAllocation) {
                    $primaryAllocation = $allocation;
                }
            }

            if (!$primaryAllocation && !empty($createdAllocations)) {
                $primaryAllocation = $createdAllocations[0];
            }
        }

        $owner = user();

        if (!$owner) {
            throw new InvalidFileUploadException(trans('admin/server.import_errors.no_user'));
        }

        $serverName = Arr::get($config, 'name', 'Imported Server');

        $startupCommand = Arr::get($config, 'settings.startup');
        if ($startupCommand === null) {
            $startupCommand = array_values($egg->startup_commands)[0];
        }

        $dockerImage = Arr::get($config, 'settings.image');
        if ($dockerImage === null) {
            $dockerImage = array_values($egg->docker_images)[0];
        }

        $server = Server::create([
            'uuid' => Str::uuid()->toString(),
            'uuid_short' => Str::uuid()->toString(),
            'name' => $serverName,
            'description' => Arr::get($config, 'description', ''),
            'owner_id' => $owner->id,
            'node_id' => $node->id,
            'allocation_id' => $primaryAllocation?->id,
            'egg_id' => $egg->id,
            'startup' => $startupCommand,
            'image' => $dockerImage,
            'skip_scripts' => Arr::get($config, 'settings.skip_scripts', false),
            'memory' => Arr::get($config, 'limits.memory', 512),
            'swap' => Arr::get($config, 'limits.swap', 0),
            'disk' => Arr::get($config, 'limits.disk', 1024),
            'io' => Arr::get($config, 'limits.io', 500),
            'cpu' => Arr::get($config, 'limits.cpu', 0),
            'threads' => Arr::get($config, 'limits.threads'),
            'oom_killer' => Arr::get($config, 'limits.oom_killer', false),
            'database_limit' => Arr::get($config, 'feature_limits.databases', 0),
            'allocation_limit' => Arr::get($config, 'feature_limits.allocations', 0),
            'backup_limit' => Arr::get($config, 'feature_limits.backups', 0),
        ]);

        if ($primaryAllocation) {
            $primaryAllocation->update(['server_id' => $server->id]);
        }

        foreach ($createdAllocations as $allocation) {
            if ($allocation->id !== $primaryAllocation?->id) {
                $allocation->update(['server_id' => $server->id]);
            }
        }

        if (isset($config['variables'])) {
            $this->importVariables($server, $config['variables']);
        }

        if (isset($config['icon'])) {
            $this->importServerIcon($server, $config['icon']);
        }

        return $server;
    }

    /**
     * Import server icon from base64 encoded data.
     *
     * @param  array<string, string>  $iconData
     */
    protected function importServerIcon(Server $server, array $iconData): void
    {
        $base64Data = Arr::get($iconData, 'data');
        $extension = Arr::get($iconData, 'extension');

        if (!$base64Data || !$extension) {
            return;
        }

        if (!array_key_exists($extension, Server::IMAGE_FORMATS)) {
            return;
        }

        try {
            $imageData = base64_decode($base64Data, true);

            if ($imageData === false) {
                return;
            }

            $path = Server::ICON_STORAGE_PATH . "/{$server->uuid}.{$extension}";
            Storage::disk('public')->put($path, $imageData);
        } catch (\Exception $e) {
            // Log the error but do not fail the entire import process
            report($e);
        }
    }

    /**
     * @param  array<int, array{env_variable: string, value: string|null}>  $variables
     */
    protected function importVariables(Server $server, array $variables): void
    {
        foreach ($variables as $variable) {
            $envVariable = Arr::get($variable, 'env_variable');
            $value = Arr::get($variable, 'value');

            /** @var EggVariable $eggVariable */
            $eggVariable = $server->egg->variables()->where('env_variable', $envVariable)->first();

            ServerVariable::create([
                'server_id' => $server->id,
                'variable_id' => $eggVariable->id,
                'variable_value' => $value,
            ]);
        }
    }

    /**
     * @throws InvalidFileUploadException
     */
    protected function findNextAvailablePort(int $nodeId, string $ip, int $startPort): int
    {
        $port = $startPort + 1;
        $maxPort = 65535;

        while ($port <= $maxPort) {
            $exists = Allocation::where('node_id', $nodeId)
                ->where('ip', $ip)
                ->where('port', $port)
                ->exists();

            if (!$exists) {
                return $port;
            }

            $port++;
        }

        throw new InvalidFileUploadException(trans('admin/server.import_errors.port_exhausted_desc', ['ip' => $ip, 'port' => $startPort]));
    }
}
