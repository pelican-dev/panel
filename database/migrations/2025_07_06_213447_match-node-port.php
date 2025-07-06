<?php

use App\Models\Node;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $nodes = Node::where('behind_proxy', false)->get();
            foreach ($nodes as $node) {
                $node->update(['daemon_connect' => $node->daemon_listen]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed
    }
};
