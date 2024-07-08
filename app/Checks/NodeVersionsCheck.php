<?php

namespace App\Checks;

use App\Models\Node;
use App\Services\Helpers\SoftwareVersionService;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class NodeVersionsCheck extends Check
{
    public function run(): Result
    {
        $all = Node::query()->count();
        $latestVersion = app(SoftwareVersionService::class)->getDaemon();

        $outdated = Node::query()->get()
            ->filter(fn (Node $node) => !isset($node->systemInformation()['exception']) && $node->systemInformation()['version'] !== $latestVersion)
            ->count();

        $result = Result::make()
            ->meta([
                'all' => $all,
                'outdated' => $outdated,
            ])
            ->shortSummary($outdated === 0 ? 'All up-to-date' : "{$outdated} outdated");

        return $outdated === 0
            ? $result->ok("All `{$all}` Nodes are up-to-date")
            : $result->failed("`{$outdated}`/`{$all}` Nodes are outdated");
    }
}
