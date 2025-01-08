<?php

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
            $table->dropColumn('file_denylist');
        });

        Schema::table('eggs', function (Blueprint $table) {
            switch (Schema::getConnection()->getDriverName()) {
                case 'mysql':
                case 'mariadb':
                case 'sqlite':
                    $table->json('file_denylist')->nullable()->after('docker_images');
                    break;
                case 'pgsql':
                    $table->jsonb('file_denylist')->nullable()->after('docker_images');
                    break;
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eggs', function (Blueprint $table) {
            $table->dropColumn('file_denylist');
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->text('file_denylist')->after('docker_images');
        });
    }
};
