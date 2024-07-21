<?php

namespace App\Http\Controllers\Admin\Servers;

use App\Enums\ServerState;
use App\Models\DatabaseHost;
use App\Models\Egg;
use App\Models\Mount;
use App\Models\Node;
use Illuminate\View\View;
use App\Models\Server;
use App\Exceptions\DisplayException;
use App\Http\Controllers\Controller;
use App\Services\Servers\EnvironmentService;
use App\Traits\Controllers\JavascriptInjection;

class ServerViewController extends Controller
{
    use JavascriptInjection;

    /**
     * ServerViewController constructor.
     */
    public function __construct(
        private readonly EnvironmentService $environmentService,
    ) {
    }

    /**
     * Returns the index view for a server.
     */
    public function index(Server $server): View
    {
        return view('admin.servers.view.index', compact('server'));
    }

    /**
     * Returns the server details page.
     */
    public function details(Server $server): View
    {
        return view('admin.servers.view.details', compact('server'));
    }

    /**
     * Returns a view of server build settings.
     */
    public function build(Server $server): View
    {
        $allocations = $server->node->allocations->toBase();

        return view('admin.servers.view.build', [
            'server' => $server,
            'assigned' => $allocations->where('server_id', $server->id)->sortBy('port')->sortBy('ip'),
            'unassigned' => $allocations->where('server_id', null)->sortBy('port')->sortBy('ip'),
        ]);
    }

    /**
     * Returns the server startup management page.
     */
    public function startup(Server $server): View
    {
        $variables = $this->environmentService->handle($server);
        $eggs = Egg::all()->keyBy('id');

        $this->plainInject([
            'server' => $server,
            'server_variables' => $variables,
            'eggs' => $eggs,
        ]);

        return view('admin.servers.view.startup', compact('server', 'eggs'));
    }

    /**
     * Returns all the databases that exist for the server.
     */
    public function database(Server $server): View
    {
        return view('admin.servers.view.database', [
            'hosts' => DatabaseHost::all(),
            'server' => $server,
        ]);
    }

    /**
     * Returns all the mounts that exist for the server.
     */
    public function mounts(Server $server): View
    {
        $server->load('mounts');

        $mounts = Mount::query()
            ->whereHas('eggs', fn ($q) => $q->where('id', $server->egg_id))
            ->whereHas('nodes', fn ($q) => $q->where('id', $server->node_id))
            ->get();

        return view('admin.servers.view.mounts', [
            'mounts' => $mounts,
            'server' => $server,
        ]);
    }

    /**
     * Returns the base server management page, or an exception if the server
     * is in a state that cannot be recovered from.
     *
     * @throws \App\Exceptions\DisplayException
     */
    public function manage(Server $server): View
    {
        if ($server->status === ServerState::InstallFailed) {
            throw new DisplayException('This server is in a failed install state and cannot be recovered. Please delete and re-create the server.');
        }

        // Check if the panel doesn't have at least 2 nodes configured.
        $nodeCount = Node::query()->count();
        $canTransfer = false;
        if ($nodeCount >= 2) {
            $canTransfer = true;
        }

        \JavaScript::put([
            'nodeData' => Node::getForServerCreation(),
        ]);

        return view('admin.servers.view.manage', [
            'nodes' => Node::all(),
            'server' => $server,
            'canTransfer' => $canTransfer,
        ]);
    }

    /**
     * Returns the server deletion page.
     */
    public function delete(Server $server): View
    {
        return view('admin.servers.view.delete', compact('server'));
    }
}
