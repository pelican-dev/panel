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
                $egg->config_files = $updatedPort;
                echo "Processed Port update with ID: {$egg->name}\n";
            }

            $updatedIp = str_replace(
                'server.build.default.ip',
                'server.allocations.default.ip',
                $egg->config_files
            );

            if ($updatedIp !== $egg->config_files) {
                $egg->config_files = $updatedIp;
                echo "Processed IP update with ID: {$egg->name}\n";
            }

            $updatedEnv = str_replace(
                'server.build.env.',
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

    public function down(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            $revertedEnv = str_replace(
                'server.environment.',
                'server.build.env.',
                $egg->config_files
            );

            if ($revertedEnv !== $egg->config_files) {
                $egg->config_files = $revertedEnv;
            }

            $revertedIp = str_replace(
                'server.allocations.default.ip',
                'server.build.default.ip',
                $egg->config_files
            );

            if ($revertedIp !== $egg->config_files) {
                $egg->config_files = $revertedIp;
            }

            $revertedPort = str_replace(
                'server.allocations.default.port',
                'server.build.default.port',
                $egg->config_files
            );

            if ($revertedPort !== $egg->config_files) {
                $egg->config_files = $revertedPort;
            }

            DB::table('eggs')
                ->where('id', $egg->id)
                ->update(['config_files' => $egg->config_files]);
        }
    }
};
