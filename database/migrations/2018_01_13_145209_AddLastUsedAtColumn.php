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
            $table->unsignedTinyInteger('key_type')->after('user_id')->default(0);
            $table->timestamp('last_used_at')->after('memo')->nullable();
            $table->dropColumn('expires_at');

            $table->dropForeign(['user_id']);
        });

        Schema::table('api_keys', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->timestamp('expires_at')->after('memo')->nullable();
            $table->dropColumn('last_used_at', 'key_type');

            $table->dropForeign(['user_id']);
        });

        Schema::table('api_keys', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
