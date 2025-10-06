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
            $table->dropForeign(['parent_service']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('service_options_parent_service_foreign');
            }

            $table->renameColumn('parent_service', 'service_id');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_options', function (Blueprint $table) {
            $table->dropForeign(['service_id']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('service_options_service_id_foreign');
            }

            $table->renameColumn('service_id', 'parent_service');
            $table->foreign('parent_service')->references('id')->on('services');
        });
    }
};
