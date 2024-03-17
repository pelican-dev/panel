<?php

namespace App\Http\Controllers\Admin\Eggs;

use Illuminate\View\View;
use App\Models\Egg;
use App\Models\EggVariable;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use App\Http\Controllers\Controller;
use App\Services\Eggs\Variables\VariableUpdateService;
use App\Http\Requests\Admin\Egg\EggVariableFormRequest;
use App\Services\Eggs\Variables\VariableCreationService;

class EggVariableController extends Controller
{
    /**
     * EggVariableController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected VariableCreationService $creationService,
        protected VariableUpdateService $updateService,
        protected ViewFactory $view
    ) {
    }

    /**
     * Handle request to view the variables attached to an Egg.
     */
    public function view(int $egg): View
    {
        $egg = Egg::with('variables')->findOrFail($egg);

        return view('admin.eggs.variables', ['egg' => $egg]);
    }

    /**
     * Handle a request to create a new Egg variable.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Egg\Variable\BadValidationRuleException
     * @throws \App\Exceptions\Service\Egg\Variable\ReservedVariableNameException
     */
    public function store(EggVariableFormRequest $request, Egg $egg): RedirectResponse
    {
        $this->creationService->handle($egg->id, $request->normalize());
        $this->alert->success(trans('admin/eggs.variables.notices.variable_created'))->flash();

        return redirect()->route('admin.eggs.variables', $egg->id);
    }

    /**
     * Handle a request to update an existing Egg variable.
     *
     * @throws \App\Exceptions\DisplayException
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Egg\Variable\ReservedVariableNameException
     */
    public function update(EggVariableFormRequest $request, Egg $egg, EggVariable $variable): RedirectResponse
    {
        $this->updateService->handle($variable, $request->normalize());
        $this->alert->success(trans('admin/eggs.variables.notices.variable_updated', [
            'variable' => $variable->name,
        ]))->flash();

        return redirect()->route('admin.eggs.variables', $egg->id);
    }

    /**
     * Handle a request to delete an existing Egg variable from the Panel.
     */
    public function destroy(int $egg, EggVariable $variable): RedirectResponse
    {
        $variable->delete();
        $this->alert->success(trans('admin/eggs.variables.notices.variable_deleted', [
            'variable' => $variable->name,
        ]))->flash();

        return redirect()->route('admin.eggs.variables', $egg);
    }
}
