<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            $updatedEnv = str_replace(
                'server.build.environment.',
                'server.environment.',
                $egg->config_files
            );

            if ($updatedEnv !== $egg->config_files) {
                $egg->config_files = $updatedEnv;
                echo "Processed ENV update with ID: {$egg->name}\n";
            }

            DB::table('eggs')
                ->where('id', $egg->id)
                ->update(['config_files' => $egg->config_files]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We shouldn't revert this...
    }
};
