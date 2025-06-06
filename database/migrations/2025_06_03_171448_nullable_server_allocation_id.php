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
        Schema::table('servers', function (Blueprint $table) {
            $table->dropForeign(['allocation_id']);
            $table->dropUnique(['allocation_id']);
            $table->unsignedInteger('allocation_id')->nullable()->change();
            $table->foreign('allocation_id')->references('id')->on('allocations')->nullOnDelete();
        });

        Schema::table('server_transfers', function (Blueprint $table) {
            $table->unsignedInteger('old_allocation')->nullable()->change();
            $table->unsignedInteger('new_allocation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed
    }
};
