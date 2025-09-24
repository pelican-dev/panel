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
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign(['location']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('nodes_location_foreign');
            }

            $table->renameColumn('location', 'location_id');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign(['location_id']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('nodes_location_id_foreign');
            }

            $table->renameColumn('location_id', 'location');
            $table->foreign('location')->references('id')->on('locations');
        });
    }
};
