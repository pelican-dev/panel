<?php

namespace App\Http\Controllers\Api\Application\Servers;

use App\Exceptions\Service\InvalidFileUploadException;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Servers\GetServerRequest;
use App\Models\Server;
use App\Services\Servers\Sharing\ServerConfigCreatorService;
use App\Services\Servers\Sharing\ServerConfigExporterService;
use App\Transformers\Api\Application\ServerTransformer;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Server Config', weight: 1)]
class ServerConfigController extends ApplicationApiController
{
    public function __construct(
        private ServerConfigExporterService $exporterService,
        private ServerConfigCreatorService $creatorService
    ) {
        parent::__construct();
    }

    /**
     * Export server configuration
     *
     * Export a server's configuration to YAML format. Returns the configuration as a
     * downloadable YAML file containing settings, limits, allocations, and variable values.
     */
    public function export(GetServerRequest $request, Server $server): Response
    {
        $options = [
            'include_description' => $request->boolean('include_description', true),
            'include_allocations' => $request->boolean('include_allocations', true),
            'include_variable_values' => $request->boolean('include_variable_values', true),
        ];

        $yaml = $this->exporterService->handle($server, $options);

        $filename = 'server-config-' . str($server->name)->kebab()->lower()->trim() . '.yaml';

        return response($yaml, 200, [
            'Content-Type' => 'application/x-yaml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Create server from configuration
     *
     * Create a new server from a YAML configuration file. The configuration must
     * include a valid egg UUID that exists in the system. Optionally specify a
     * node_id to create the server on a specific node.
     *
     * @throws InvalidFileUploadException
     */
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:yaml,yml|max:1024',
            'node_id' => 'required|integer|exists:nodes,id',
        ]);

        $file = $request->file('file');
        $nodeId = $request->input('node_id');

        $server = $this->creatorService->fromFile($file, $nodeId);

        return $this->fractal->item($server)
            ->transformWith($this->getTransformer(ServerTransformer::class))
            ->respond(201);
    }
}
