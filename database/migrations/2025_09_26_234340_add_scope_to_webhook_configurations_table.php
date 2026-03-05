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
        Schema::table('webhook_configurations', function (Blueprint $table) {
            $table->string('scope')->default('global')->after('id');
            $table->unsignedBigInteger('server_id')->nullable()->after('scope');
            $table->foreign('server_id')->references('id')->on('servers')->onDelete('cascade');

            $table->index(['scope', 'server_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webhook_configurations', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropIndex(['scope', 'server_id']);
            $table->dropColumn(['scope', 'server_id']);
        });
    }
};
