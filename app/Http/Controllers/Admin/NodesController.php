<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use App\Models\Node;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use App\Http\Controllers\Controller;
use App\Services\Nodes\NodeUpdateService;
use Illuminate\Cache\Repository as CacheRepository;
use App\Services\Nodes\NodeCreationService;
use App\Services\Nodes\NodeDeletionService;
use App\Services\Helpers\SoftwareVersionService;
use App\Http\Requests\Admin\Node\NodeFormRequest;

class NodesController extends Controller
{
    /**
     * NodesController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected CacheRepository $cache,
        protected NodeCreationService $creationService,
        protected NodeDeletionService $deletionService,
        protected NodeUpdateService $updateService,
        protected SoftwareVersionService $versionService,
        protected ViewFactory $view
    ) {
    }

    /**
     * Displays create new node page.
     */
    public function create(): View|RedirectResponse
    {
        return view('admin.nodes.new');
    }

    /**
     * Updates settings for a node.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function updateSettings(NodeFormRequest $request, Node $node): RedirectResponse
    {
        $this->updateService->handle($node, $request->normalize(), $request->input('reset_secret') === 'on');
        $this->alert->success(trans('admin/node.notices.node_updated'))->flash();

        return redirect()->route('admin.nodes.view.settings', $node->id)->withInput();
    }

    /**
     * Deletes a node from the system.
     *
     * @throws \App\Exceptions\DisplayException
     */
    public function delete(int|Node $node): RedirectResponse
    {
        $this->deletionService->handle($node);
        $this->alert->success(trans('admin/node.notices.node_deleted'))->flash();

        return redirect()->route('admin.nodes');
    }
}
