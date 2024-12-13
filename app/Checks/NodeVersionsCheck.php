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
            $result = Result::make()->notificationMessage('No Nodes created')->shortSummary('No Nodes');
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
            ->shortSummary($outdated === 0 ? 'All up-to-date' : "{$outdated}/{$all} outdated");

        return $outdated === 0
            ? $result->ok('All Nodes are up-to-date.')
            : $result->failed(':outdated/:all Nodes are outdated.');
    }
}
