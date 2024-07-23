<?php

namespace App\Console\Commands\Maintenance;

use App\Models\Node;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PruneImagesCommand extends Command
{
    protected $signature = 'p:maintenance:prune-images {node?}';

    protected $description = 'Clean up all dangling docker images to clear up disk space.';

    public function handle(): void
    {
        $node = $this->argument('node');

        if (empty($node)) {
            $nodes = Node::all();
            /** @var Node $node */
            foreach ($nodes as $node) {
                $this->cleanupImages($node);
            }
        } else {
            $this->cleanupImages((int) $node);
        }
    }

    private function cleanupImages(int|Node $node): void
    {
        if (!$node instanceof Node) {
            $node = Node::query()->findOrFail($node);
        }

        try {
            $response = Http::daemon($node)
                ->connectTimeout(5)
                ->timeout(30)
                ->delete('/api/system/docker/image/prune')
                ->json() ?? [];

            if (empty($response) || $response['ImagesDeleted'] === null) {
                $this->warn("Node {$node->id}: No images to clean up.");

                return;
            }

            $count = count($response['ImagesDeleted']);

            $useBinaryPrefix = config('panel.use_binary_prefix');
            $space = round($useBinaryPrefix ? $response['SpaceReclaimed'] / 1024 / 1024 : $response['SpaceReclaimed'] / 1000 / 1000, 2) . ($useBinaryPrefix ? ' MiB' : ' MB');

            $this->info("Node {$node->id}: Cleaned up {$count} dangling docker images. ({$space})");
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
