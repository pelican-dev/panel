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
        Schema::table('subusers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('server_id')->references('id')->on('servers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subusers', function (Blueprint $table) {
            $table->dropForeign('subusers_user_id_foreign');
            $table->dropForeign('subusers_server_id_foreign');

            $table->dropIndex('subusers_user_id_foreign');
            $table->dropIndex('subusers_server_id_foreign');
        });
    }
};
