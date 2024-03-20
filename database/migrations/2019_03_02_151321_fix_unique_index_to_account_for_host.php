<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropUnique(['database']);
            $table->dropUnique(['username']);

            $table->unique(['database_host_id', 'database']);
            $table->unique(['database_host_id', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropForeign(['database_host_id']);

            $table->dropUnique(['database_host_id', 'database']);
            $table->dropUnique(['database_host_id', 'username']);

            $table->unique(['database']);
            $table->unique(['username']);
        });
    }
};
