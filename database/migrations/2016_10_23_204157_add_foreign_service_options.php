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
        Schema::table('service_options', function (Blueprint $table) {
            $table->unsignedInteger('parent_service')->change();
        });

        Schema::table('service_options', function (Blueprint $table) {
            $table->foreign('parent_service')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_options', function (Blueprint $table) {
            $table->dropForeign('service_options_parent_service_foreign');
            $table->dropIndex('service_options_parent_service_foreign');
        });

        Schema::table('service_options', function (Blueprint $table) {
            $table->unsignedMediumInteger('parent_service')->change();
        });
    }
};
