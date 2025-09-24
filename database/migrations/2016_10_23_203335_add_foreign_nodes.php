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
            $table->unsignedInteger('location')->change();
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->foreign('location')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign('nodes_location_foreign');
            $table->dropIndex('nodes_location_foreign');
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->unsignedMediumInteger('location')->change();
        });
    }
};
