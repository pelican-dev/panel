<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $eggIconsPath = public_path('storage/icons/egg');
        $serverIconsPath = public_path('storage/icons/server');

        if (!file_exists($eggIconsPath)) {
            mkdir($eggIconsPath, 0755, true);
        }

        if (!file_exists($serverIconsPath)) {
            mkdir($serverIconsPath, 0755, true);
        }

        $eggs = DB::table('eggs')->whereNotNull('image')->get();
        foreach ($eggs as $egg) {
            if (!empty($egg->image) && str_starts_with($egg->image, 'data:')) {
                $this->convertBase64ToFile($egg->image, $egg->uuid, $eggIconsPath);
            }
        }

        $servers = DB::table('servers')->whereNotNull('icon')->get();
        foreach ($servers as $server) {
            if (!empty($server->icon) && str_starts_with($server->icon, 'data:')) {
                $this->convertBase64ToFile($server->icon, $server->uuid, $serverIconsPath);
            }
        }

        Schema::table('eggs', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //None: This migration is irreversible.
    }

    /**
     * Convert a base64 image string to a file.
     */
    private function convertBase64ToFile(string $base64String, string $uuid, string $directory): void
    {
        if (!preg_match('/^data:image\/([\w+]+);base64,(.+)$/', $base64String, $matches)) {
            return;
        }

        $extension = $matches[1];
        $data = base64_decode($matches[2]);

        if (!$data) {
            return;
        }

        // Save the file
        $filename = "{$uuid}.{$extension}";
        $filepath = "{$directory}/{$filename}";

        file_put_contents($filepath, $data);
    }
};
