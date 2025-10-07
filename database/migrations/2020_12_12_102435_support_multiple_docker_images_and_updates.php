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
        Schema::table('eggs', function (Blueprint $table) {
            $table->json('docker_images')->after('docker_image')->nullable();
            $table->text('update_url')->after('docker_images')->nullable();
        });

        Schema::table('eggs', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() === 'pgsql') {
                DB::statement('UPDATE eggs SET docker_images = json_build_array(docker_image)');
            } else {
                DB::statement('UPDATE eggs SET docker_images = JSON_ARRAY(docker_image)');
            }
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->dropColumn('docker_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eggs', function (Blueprint $table) {
            $table->text('docker_image')->after('docker_images');
        });

        Schema::table('eggs', function (Blueprint $table) {
            DB::statement('UPDATE eggs SET docker_image = JSON_UNQUOTE(JSON_EXTRACT(docker_images, "$[0]"))');
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->dropColumn('docker_images');
            $table->dropColumn('update_url');
        });
    }
};
