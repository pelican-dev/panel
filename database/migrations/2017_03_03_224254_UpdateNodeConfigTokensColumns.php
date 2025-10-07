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
        Schema::table('node_configuration_tokens', function (Blueprint $table) {
            $table->dropForeign(['node']);

            $table->dropColumn('expires_at');
            $table->renameColumn('node', 'node_id');

            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('node_configuration_tokens', function (Blueprint $table) {
            $table->dropForeign(['node_id']);

            $table->renameColumn('node_id', 'node');
            $table->timestamp('expires_at')->after('token');

            $table->foreign('node')->references('id')->on('nodes');
        });
    }
};
