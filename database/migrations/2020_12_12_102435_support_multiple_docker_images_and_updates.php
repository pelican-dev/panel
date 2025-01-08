<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eggs', function (Blueprint $table) {
            switch (Schema::getConnection()->getDriverName()) {
                case 'mysql':
                case 'mariadb':
                case 'sqlite':
                    $table->json('docker_images')->after('docker_image')->nullable();
                    break;
                case 'pgsql':
                    $table->jsonb('docker_images')->after('docker_image')->nullable();
                    break;
            }
            $table->text('update_url')->after('docker_images')->nullable();
        });

        Schema::table('eggs', function (Blueprint $table) {
            switch (Schema::getConnection()->getDriverName()) {
                case 'sqlite':
                case 'mariadb':
                case 'mysql':
                    DB::statement('UPDATE `eggs` SET `docker_images` = JSON_ARRAY(docker_image)');
                    break;
                case 'pgsql':
                    DB::statement('UPDATE eggs SET docker_images = to_jsonb(ARRAY[docker_image])');
                    break;
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
            switch (Schema::getConnection()->getDriverName()) {
                case 'sqlite':
                case 'mariadb':
                case 'mysql':
                    DB::statement('UPDATE `eggs` SET `docker_image` = JSON_UNQUOTE(JSON_EXTRACT(docker_images, "$[0]"))');
                    break;
                case 'pgsql':
                    DB::statement('UPDATE eggs SET docker_image = (docker_images->>0)::text');
                    break;
            }
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->dropColumn('docker_images');
            $table->dropColumn('update_url');
        });
    }
};
