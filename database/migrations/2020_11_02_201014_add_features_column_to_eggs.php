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
            switch (Schema::getConnection()->getDriverName()) {
                case 'mysql':
                case 'mariadb':
                case 'sqlite':
                    $table->json('features')->after('description')->nullable();
                    break;
                case 'pgsql':
                    $table->jsonb('features')->after('description')->nullable();
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
            $table->dropColumn('features');
        });
    }
};
