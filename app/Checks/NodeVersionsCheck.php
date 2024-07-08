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
        $outdated = 0;

        $latestVersion = app(SoftwareVersionService::class)->getDaemon();

        $nodes = Node::query()->get();
        foreach ($nodes as $node) {
            if ($node->systemInformation()['version'] === $latestVersion) {
                $outdated++;
            }
        }

        $result = Result::make()
            ->meta([
                'all' => $all,
                'outdated' => $outdated,
            ])
            ->shortSummary($outdated === 0);

        return $outdated === 0
            ? $result->ok('All Nodes are up-to-date')
            : $result->failed("`{$outdated}`/`{$all}` Nodes are outdated");
    }
}
