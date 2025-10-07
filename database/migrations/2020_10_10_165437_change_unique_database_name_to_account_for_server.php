<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropUnique(['database_host_id', 'database']);
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->unique(['database_host_id', 'server_id', 'database']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropUnique(['database_host_id', 'server_id', 'database']);
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->unique(['database_host_id', 'database']);
        });
    }
};
