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
            $table->dropForeign('nodes_location_id_foreign');
            $table->dropColumn('location_id');
        });

        Schema::drop('locations');

        Schema::table('api_keys', function (Blueprint $table) {
            $table->dropColumn('r_locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('short');
            $table->text('long')->nullable();
            $table->timestamps();
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->unsignedInteger('location_id')->default(0);
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::table('api_keys', function (Blueprint $table) {
            $table->unsignedTinyInteger('r_locations')->default(0);
        });
    }
};
