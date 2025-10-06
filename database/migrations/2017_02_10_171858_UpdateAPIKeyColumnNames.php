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
        Schema::table('api_keys', function (Blueprint $table) {
            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropForeign('api_keys_user_foreign');
                $table->dropIndex('api_keys_user_foreign');
            }

            $table->renameColumn('user', 'user_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            if (!in_array(Schema::getConnection()->getDriverName(), ['sqlite', 'pgsql'])) {
                $table->dropForeign('api_keys_user_id_foreign');
                $table->dropIndex('api_keys_user_id_foreign');
            }

            $table->renameColumn('user_id', 'user');
            $table->foreign('user')->references('id')->on('users');
        });
    }
};
