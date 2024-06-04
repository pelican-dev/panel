<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $eggs = DB::table('eggs')->get();

        foreach ($eggs as $egg) {
            try {
                $updatedEnv = str_replace(
                    'server.build.env.',
                    'server.environment.',
                    $egg->config_files
                );

                if ($updatedEnv !== $egg->config_files) {
                    DB::table('eggs')
                        ->where('id', $egg->id)
                        ->update(['config_files' => $updatedEnv]);
                    echo "Processed ENV update with ID: {$egg->name}\n";
                }
            } catch (\Exception $e) {
                // Log the error
                echo "Failed to process row with ID: {$egg->name}. Error: " . $e->getMessage();
            }

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
                DB::table('eggs')
                    ->where('id', $egg->id)
                    ->update(['config_files' => $revertedEnv]);
            }
        }
    }
};
