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
        Schema::table('service_packs', function (Blueprint $table) {
            $table->dropForeign(['option']);

            switch (Schema::getConnection()->getDriverName()) {
                case 'mariadb':
                case 'mysql':
                    $table->dropIndex('service_packs_option_foreign');
                    break;
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

            switch (Schema::getConnection()->getDriverName()) {
                case 'mariadb':
                case 'mysql':
                    $table->dropIndex('service_packs_option_id_foreign');
                    break;
            }

            $table->renameColumn('option_id', 'option');
            $table->foreign('option')->references('id')->on('service_options');
        });
    }
};
