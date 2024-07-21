<?php

namespace App\Http\Controllers\Admin\Eggs;

use App\Exceptions\Service\Egg\NoParentConfigurationFoundException;
use Illuminate\View\View;
use App\Models\Egg;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Egg\EggFormRequest;
use Ramsey\Uuid\Uuid;

class EggController extends Controller
{
    /**
     * EggController constructor.
     */
    public function __construct(
        protected AlertsMessageBag $alert,
        protected ViewFactory $view
    ) {
    }

    /**
     * Render eggs listing page.
     */
    public function index(): View
    {
        return view('admin.eggs.index', [
            'eggs' => Egg::all(),
        ]);
    }

    /**
     * Handle a request to display the Egg creation page.
     */
    public function create(): View
    {
        $eggs = Egg::all();
        \JavaScript::put(['eggs' => $eggs->keyBy('id')]);

        return view('admin.eggs.new', ['eggs' => $eggs]);
    }

    /**
     * Handle request to store a new Egg.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Egg\NoParentConfigurationFoundException
     */
    public function store(EggFormRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['docker_images'] = $this->normalizeDockerImages($data['docker_images'] ?? null);
        $data['author'] = $request->user()->email;

        $data['config_from'] = array_get($data, 'config_from');
        if (!is_null($data['config_from'])) {
            $parentEgg = Egg::query()->find(array_get($data, 'config_from'));
            throw_unless($parentEgg, new NoParentConfigurationFoundException(trans('exceptions.egg.invalid_copy_id')));
        }

        $egg = Egg::query()->create(array_merge($data, [
            'uuid' => Uuid::uuid4()->toString(),
        ]));

        $this->alert->success(trans('admin/eggs.notices.egg_created'))->flash();

        return redirect()->route('admin.eggs.view', $egg->id);
    }

    /**
     * Handle request to view a single Egg.
     */
    public function view(Egg $egg): View
    {
        return view('admin.eggs.view', [
            'egg' => $egg,
            'images' => array_map(
                fn ($key, $value) => $key === $value ? $value : "$key|$value",
                array_keys($egg->docker_images),
                $egg->docker_images,
            ),
        ]);
    }

    /**
     * Handle request to update an Egg.
     *
     * @throws \App\Exceptions\Model\DataValidationException
     * @throws \App\Exceptions\Service\Egg\NoParentConfigurationFoundException
     */
    public function update(EggFormRequest $request, Egg $egg): RedirectResponse
    {
        $data = $request->validated();
        $data['docker_images'] = $this->normalizeDockerImages($data['docker_images'] ?? null);

        $eggId = array_get($data, 'config_from');
        $copiedFromEgg = Egg::query()->find($eggId);

        throw_unless($copiedFromEgg, new NoParentConfigurationFoundException(trans('exceptions.egg.invalid_copy_id')));

        $egg->update($data);

        $this->alert->success(trans('admin/eggs.notices.updated'))->flash();

        return redirect()->route('admin.eggs.view', $egg->id);
    }

    /**
     * Handle request to destroy an egg.
     *
     * @throws \App\Exceptions\Service\Egg\HasChildrenException
     * @throws \App\Exceptions\Service\HasActiveServersException
     */
    public function destroy(Egg $egg): RedirectResponse
    {
        $egg->delete();

        $this->alert->success(trans('admin/eggs.notices.deleted'))->flash();

        return redirect()->route('admin.eggs.view', $egg->id);
    }

    /**
     * Normalizes a string of docker image data into the expected egg format.
     */
    protected function normalizeDockerImages(string $input = null): array
    {
        $data = array_map(fn ($value) => trim($value), explode("\n", $input ?? ''));

        $images = [];
        // Iterate over the image data provided and convert it into a name => image
        // pairing that is used to improve the display on the front-end.
        foreach ($data as $value) {
            $parts = explode('|', $value, 2);
            $images[$parts[0]] = empty($parts[1]) ? $parts[0] : $parts[1];
        }

        return $images;
    }
}
