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
        Schema::table('service_packs', function (Blueprint $table) {
            $table->dropForeign(['option']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('service_packs_option_foreign');
            }

            $table->renameColumn('option', 'option_id');
            $table->foreign('option_id')->references('id')->on('service_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_packs', function (Blueprint $table) {
            $table->dropForeign(['option_id']);

            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropIndex('service_packs_option_id_foreign');
            }

            $table->renameColumn('option_id', 'option');
            $table->foreign('option')->references('id')->on('service_options');
        });
    }
};
