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
            $table->dropForeign(['option_id']);

            $table->foreign('option_id')->references('id')->on('service_options')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_variables', function (Blueprint $table) {
            $table->dropForeign(['option_id']);

            $table->foreign('option_id')->references('id')->on('service_options');
        });
    }
};
