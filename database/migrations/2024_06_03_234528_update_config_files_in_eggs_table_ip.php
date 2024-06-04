<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            $updatedIp = str_replace(
                'server.build.default.ip',
                'server.allocations.default.ip',
                $egg->config_files
            );

            if ($updatedIp !== $egg->config_files) {
                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['config_files' => $updatedIp]);
                echo "Processed IP update with ID: {$egg->name}\n";
            }
        }
    }

    public function down(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            $revertedIp = str_replace(
                'server.allocations.default.ip',
                'server.build.default.ip',
                $egg->config_files
            );

            if ($revertedIp !== $egg->config_files) {
                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['config_files' => $revertedIp]);
            }
        }
    }
};
