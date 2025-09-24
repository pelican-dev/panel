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
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropForeign(['server_id']);

            $table->foreign('server_id')->references('id')->on('servers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropForeign(['server_id']);

            $table->foreign('server_id')->references('id')->on('servers');
        });
    }
};
