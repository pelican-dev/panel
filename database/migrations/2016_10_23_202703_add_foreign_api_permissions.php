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
        Schema::table('api_permissions', function (Blueprint $table) {
            $table->unsignedInteger('key_id')->change();
        });

        Schema::table('api_permissions', function (Blueprint $table) {
            $table->foreign('key_id')->references('id')->on('api_keys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_permissions', function (Blueprint $table) {
            $table->dropForeign('api_permissions_key_id_foreign');
            $table->dropIndex('api_permissions_key_id_foreign');
        });

        Schema::table('api_permissions', function (Blueprint $table) {
            $table->unsignedMediumInteger('key_id')->change();
        });
    }
};
