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
        Schema::table('servers', function (Blueprint $table) {
            $table->json('ports');
        });

        Schema::dropIfExists('allocations');

        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['allocation_id', 'allocation_limit']);
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->boolean('strict_ports')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('strict_ports');
        });

        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('node_id');
            $table->string('ip');
            $table->text('ip_alias');
            $table->unsignedMediumInteger('port');
            $table->unsignedInteger('server_id');
            $table->string('notes')->default('');
            $table->timestamps();

            $table->unique(['node_id', 'ip', 'port']);
        });
    }
};
