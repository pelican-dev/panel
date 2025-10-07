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
            $table->dropForeign(['node']);
            $table->dropForeign(['assigned_to']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('allocations_node_foreign');
                $table->dropIndex('allocations_assigned_to_foreign');
            }

            $table->renameColumn('node', 'node_id');
            $table->renameColumn('assigned_to', 'server_id');
            $table->foreign('node_id')->references('id')->on('nodes');
            $table->foreign('server_id')->references('id')->on('servers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allocations', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['server_id']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('allocations_node_id_foreign');
                $table->dropIndex('allocations_server_id_foreign');
            }

            $table->renameColumn('node_id', 'node');
            $table->renameColumn('server_id', 'assigned_to');
            $table->foreign('node')->references('id')->on('nodes');
            $table->foreign('assigned_to')->references('id')->on('servers');
        });
    }
};
