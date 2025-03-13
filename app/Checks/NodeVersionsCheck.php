<?php

namespace App\Checks;

use App\Models\Node;
use App\Services\Helpers\SoftwareVersionService;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Spatie\Health\Enums\Status;

class NodeVersionsCheck extends Check
{
    public function __construct(private SoftwareVersionService $versionService) {}

    public function run(): Result
    {
        $all = Node::query()->count();

        if ($all === 0) {
            $result = Result::make()
                ->notificationMessage(trans('admin/health.results.nodeversions.no_nodes_created'))
                ->shortSummary(trans('admin/health.results.nodeversions.no_nodes'));
            $result->status = Status::skipped();

            return $result;
        }

        $latestVersion = $this->versionService->latestWingsVersion();

        $outdated = Node::query()->get()
            ->filter(fn (Node $node) => !isset($node->systemInformation()['exception']) && $node->systemInformation()['version'] !== $latestVersion)
            ->count();

        $result = Result::make()
            ->meta([
                'all' => $all,
                'outdated' => $outdated,
            ])
            ->shortSummary($outdated === 0 ? trans('admin/health.results.nodeversions.all_up_to_date') : trans('admin/health.results.nodeversions.outdated', ['outdated' => $outdated, 'all' => $all]));

        return $outdated === 0
            ? $result->ok(trans('admin/health.results.nodeversions.ok'))
            : $result->failed(trans('admin/health.results.nodeversions.failed', ['outdated' => $outdated, 'all' => $all]));
    }
}
