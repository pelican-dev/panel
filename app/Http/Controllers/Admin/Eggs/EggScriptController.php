<?php

namespace App\Http\Controllers\Admin\Eggs;

use Illuminate\View\View;
use App\Models\Egg;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use App\Http\Controllers\Controller;
use App\Services\Eggs\Scripts\InstallScriptService;
use App\Http\Requests\Admin\Egg\EggScriptFormRequest;

class EggScriptController extends Controller
{
    /**
     * EggScriptController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected InstallScriptService $installScriptService,
        protected ViewFactory $view
    ) {
    }

    /**
     * Handle requests to render installation script for an Egg.
     */
    public function index(int $egg): View
    {
        $egg = Egg::with('scriptFrom', 'configFrom')
            ->where('id', $egg)
            ->firstOrFail();

        $copy = Egg::query()
            ->whereNull('copy_script_from')
            ->whereNot('id', $egg->id)
            ->firstOrFail();

        $rely = Egg::query()->where('copy_script_from', $egg->id)->firstOrFail();

        return view('admin.eggs.scripts', [
            'copyFromOptions' => $copy,
            'relyOnScript' => $rely,
            'egg' => $egg,
        ]);
    }

    /**
     * Handle a request to update the installation script for an Egg.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     */
    public function update(EggScriptFormRequest $request, Egg $egg): RedirectResponse
    {
        $this->installScriptService->handle($egg, $request->normalize());
        $this->alert->success(trans('admin/eggs.notices.script_updated'))->flash();

        return redirect()->route('admin.eggs.scripts', $egg);
    }
}
