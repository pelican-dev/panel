<?php

namespace App\Http\Controllers\Admin\Nodes;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Node;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Controller;

class NodeController extends Controller
{
    /**
     * Returns a listing of nodes on the system.
     */
    public function index(Request $request): View
    {
        $nodes = QueryBuilder::for(
            Node::query()->withCount('servers')
        )
            ->allowedFilters(['uuid', 'name'])
            ->allowedSorts(['id'])
            ->paginate(25);

        return view('admin.nodes.index', ['nodes' => $nodes]);
    }
}
