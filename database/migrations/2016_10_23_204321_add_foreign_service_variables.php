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
        Schema::table('service_variables', function (Blueprint $table) {
            $table->unsignedInteger('option_id')->change();
        });

        Schema::table('service_variables', function (Blueprint $table) {
            $table->foreign('option_id')->references('id')->on('service_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_variables', function (Blueprint $table) {
            $table->dropForeign('service_variables_option_id_foreign');
            $table->dropIndex('service_variables_option_id_foreign');
        });

        Schema::table('service_variables', function (Blueprint $table) {
            $table->unsignedMediumInteger('option_id')->change();
        });
    }
};
