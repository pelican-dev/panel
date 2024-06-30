<?php

namespace App\Jobs;

use App\Models\Node;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NodeStatistics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach (Node::all() as $node) {
            $key = "nodes.$node->id.cpu";
            $stats = $node->statistics();
            $data = cache()->get($key, []);

            $data[now()->getTimestamp()] = $stats['cpu_percent'] ?? 0;

            cache()->put($key, $data, now()->addMinute());
        }
    }
}
