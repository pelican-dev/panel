<?php

namespace App\Http\Controllers\Api\Application\Eggs;

use App\Enums\EggFormat;
use App\Http\Controllers\Api\Application\ApplicationApiController;
use App\Http\Requests\Api\Application\Eggs\ExportEggRequest;
use App\Http\Requests\Api\Application\Eggs\GetEggRequest;
use App\Http\Requests\Api\Application\Eggs\GetEggsRequest;
use App\Models\Egg;
use App\Services\Eggs\Sharing\EggExporterService;
use App\Transformers\Api\Application\EggTransformer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EggController extends ApplicationApiController
{
    public function __construct(
        private EggExporterService $exporterService,
    ) {
        parent::__construct();
    }

    /**
     * List eggs
     *
     * Return all eggs
     *
     * @return array<mixed>
     */
    public function index(GetEggsRequest $request): array
    {
        return $this->fractal->collection(Egg::all())
            ->transformWith($this->getTransformer(EggTransformer::class))
            ->toArray();
    }

    /**
     * View egg
     *
     * Return a single egg that exists
     *
     * @return array<mixed>
     */
    public function view(GetEggRequest $request, Egg $egg): array
    {
        return $this->fractal->item($egg)
            ->transformWith($this->getTransformer(EggTransformer::class))
            ->toArray();
    }

    /**
     * Export egg
     *
     * Return a single egg as yaml or json file (defaults to YAML)
     */
    public function export(ExportEggRequest $request, Egg $egg): StreamedResponse
    {
        $format = EggFormat::tryFrom($request->input('format')) ?? EggFormat::YAML;

        return response()->streamDownload(function () use ($egg, $format) {
            echo $this->exporterService->handle($egg->id, $format);
        }, 'egg-' . $egg->getKebabName() . '.' . $format->value, [
            'Content-Type' => 'application/' . $format->value,
        ]);
    }
}
