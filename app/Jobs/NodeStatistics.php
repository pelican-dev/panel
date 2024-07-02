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
            $stats = $node->statistics();
            $timestamp = now()->getTimestamp();

            foreach ($stats as $key => $value) {
                $cacheKey = "nodes.{$node->id}.$key";
                $data = cache()->get($cacheKey, []);

                // Add current timestamp and value to the data array
                $data[$timestamp] = $value;

                // Update the cache with the new data, expires in 1 minute
                cache()->put($cacheKey, $data, now()->addMinute());
            }
        }
    }

}
