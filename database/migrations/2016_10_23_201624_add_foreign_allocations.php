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
            $table->unsignedInteger('assigned_to')->nullable()->change();
            $table->unsignedInteger('node')->change();
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->foreign('assigned_to')->references('id')->on('servers');
            $table->foreign('node')->references('id')->on('nodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropForeign('allocations_assigned_to_foreign');
            $table->dropForeign('allocations_node_foreign');

            $table->dropIndex('allocations_assigned_to_foreign');
            $table->dropIndex('allocations_node_foreign');
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->unsignedMediumInteger('assigned_to')->change();
            $table->unsignedMediumInteger('node')->change();
        });
    }
};
