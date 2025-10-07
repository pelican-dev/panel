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
        Schema::table('server_variables', function (Blueprint $table) {
            $table->unsignedInteger('server_id')->change();
            $table->unsignedInteger('variable_id')->change();
        });

        Schema::table('server_variables', function (Blueprint $table) {
            $table->foreign('server_id')->references('id')->on('servers');
            $table->foreign('variable_id')->references('id')->on('service_variables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_variables', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropForeign(['variable_id']);
        });

        Schema::table('server_variables', function (Blueprint $table) {
            $table->unsignedMediumInteger('server_id')->change();
            $table->unsignedMediumInteger('variable_id')->change();
        });

    }
};
