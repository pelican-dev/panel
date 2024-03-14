<?php

namespace App\Http\Controllers\Admin\Servers;

use App\Models\Egg;
use App\Models\Node;
use App\Repositories\Eloquent\EggRepository;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Server;
use App\Exceptions\DisplayException;
use App\Http\Controllers\Controller;
use App\Services\Servers\EnvironmentService;
use Illuminate\Contracts\View\Factory as ViewFactory;
use App\Repositories\Eloquent\NodeRepository;
use App\Repositories\Eloquent\MountRepository;
use App\Repositories\Eloquent\ServerRepository;
use App\Traits\Controllers\JavascriptInjection;
use App\Repositories\Eloquent\DatabaseHostRepository;

class ServerViewController extends Controller
{
    use JavascriptInjection;

    /**
     * ServerViewController constructor.
     */
    public function __construct(
        private DatabaseHostRepository $databaseHostRepository,
        private MountRepository $mountRepository,
        private EggRepository $eggRepository,
        private NodeRepository $nodeRepository,
        private ServerRepository $repository,
        private EnvironmentService $environmentService,
        private ViewFactory $view
    ) {
    }

    /**
     * Returns the index view for a server.
     */
    public function index(Request $request, Server $server): View
    {
        return $this->view->make('admin.servers.view.index', compact('server'));
    }

    /**
     * Returns the server details page.
     */
    public function details(Request $request, Server $server): View
    {
        return $this->view->make('admin.servers.view.details', compact('server'));
    }

    /**
     * Returns a view of server build settings.
     */
    public function build(Request $request, Server $server): View
    {
        $allocations = $server->node->allocations->toBase();

        return $this->view->make('admin.servers.view.build', [
            'server' => $server,
            'assigned' => $allocations->where('server_id', $server->id)->sortBy('port')->sortBy('ip'),
            'unassigned' => $allocations->where('server_id', null)->sortBy('port')->sortBy('ip'),
        ]);
    }

    /**
     * Returns the server startup management page.
     *
     * @throws \App\Exceptions\Repository\RecordNotFoundException
     */
    public function startup(Request $request, Server $server): View
    {
        $variables = $this->environmentService->handle($server);
        $eggs = Egg::all()->keyBy('id');

        $this->plainInject([
            'server' => $server,
            'server_variables' => $variables,
            'eggs' => $eggs,
        ]);

        return $this->view->make('admin.servers.view.startup', compact('server', 'eggs'));
    }

    /**
     * Returns all the databases that exist for the server.
     */
    public function database(Request $request, Server $server): View
    {
        return $this->view->make('admin.servers.view.database', [
            'hosts' => $this->databaseHostRepository->all(),
            'server' => $server,
        ]);
    }

    /**
     * Returns all the mounts that exist for the server.
     */
    public function mounts(Request $request, Server $server): View
    {
        $server->load('mounts');

        return $this->view->make('admin.servers.view.mounts', [
            'mounts' => $this->mountRepository->getMountListForServer($server),
            'server' => $server,
        ]);
    }

    /**
     * Returns the base server management page, or an exception if the server
     * is in a state that cannot be recovered from.
     *
     * @throws \App\Exceptions\DisplayException
     */
    public function manage(Request $request, Server $server): View
    {
        if ($server->status === Server::STATUS_INSTALL_FAILED) {
            throw new DisplayException('This server is in a failed install state and cannot be recovered. Please delete and re-create the server.');
        }

        // Check if the panel doesn't have at least 2 nodes configured.
        $nodes = $this->nodeRepository->all();
        $canTransfer = false;
        if (count($nodes) >= 2) {
            $canTransfer = true;
        }

        \JavaScript::put([
            'nodeData' => $this->nodeRepository->getNodesForServerCreation(),
        ]);

        return $this->view->make('admin.servers.view.manage', [
            'nodes' => Node::all(),
            'server' => $server,
            'canTransfer' => $canTransfer,
        ]);
    }

    /**
     * Returns the server deletion page.
     */
    public function delete(Request $request, Server $server): View
    {
        return $this->view->make('admin.servers.view.delete', compact('server'));
    }
}
