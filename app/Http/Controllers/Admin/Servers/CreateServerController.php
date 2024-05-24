<?php

namespace App\Http\Controllers\Admin\Servers;

use App\Models\Egg;
use Illuminate\View\View;
use App\Models\Node;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServerFormRequest;
use App\Services\Servers\ServerCreationService;

class CreateServerController extends Controller
{
    /**
     * CreateServerController constructor.
     */
    public function __construct(
        private AlertsMessageBag $alert,
        private ServerCreationService $creationService,
    ) {
    }

    /**
     * Displays the create server page.
     */
    public function index(): View|RedirectResponse
    {
        $nodes = Node::all();
        if (count($nodes) < 1) {
            $this->alert->warning(trans('admin/server.alerts.node_required'))->flash();

            return redirect()->route('admin.nodes');
        }

        $eggs = Egg::with('variables')->get();

        \JavaScript::put([
            'nodeData' => Node::getForServerCreation(),
            'eggs' => $eggs->keyBy('id'),
        ]);

        return view('admin.servers.new', [
            'eggs' => $eggs,
            'nodes' => Node::all(),
        ]);
    }

    /**
     * Create a new server on the remote system.
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Service\Deployment\NoViableAllocationException
     * @throws \Throwable
     */
    public function store(ServerFormRequest $request): RedirectResponse
    {
        $data = $request->except(['_token']);
        if (!empty($data['custom_image'])) {
            $data['image'] = $data['custom_image'];
            unset($data['custom_image']);
        }

        $server = $this->creationService->handle($data);

        $this->alert->success(trans('admin/server.alerts.server_created'))->flash();

        return new RedirectResponse('/admin/servers/view/' . $server->id);
    }
}
