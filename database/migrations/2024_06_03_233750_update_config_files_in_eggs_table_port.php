<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            $updatedPort = str_replace(
                'server.build.default.port',
                'server.allocations.default.port',
                $egg->config_files
            );

            if ($updatedPort !== $egg->config_files) {
                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['config_files' => $updatedPort]);
                echo "Processed Port update with ID: {$egg->name}\n";
            }
        }
    }

    public function down(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            $revertedPort = str_replace(
                'server.allocations.default.port',
                'server.build.default.port',
                $egg->config_files
            );

            if ($revertedPort !== $egg->config_files) {
                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['config_files' => $revertedPort]);
            }
        }
    }
};
