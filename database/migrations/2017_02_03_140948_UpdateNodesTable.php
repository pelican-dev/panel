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
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign(['location']);

            switch (Schema::getConnection()->getDriverName()) {
                case 'mariadb':
                case 'mysql':
                    $table->dropIndex('nodes_location_foreign');
                    break;
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

            switch (Schema::getConnection()->getDriverName()) {
                case 'mariadb':
                case 'mysql':
                    $table->dropIndex('nodes_location_id_foreign');
                    break;
            }

            $table->renameColumn('location_id', 'location');
            $table->foreign('location')->references('id')->on('locations');
        });
    }
};
