<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            Schema::table('server_transfers', function (Blueprint $table) {
                $table->dropColumn(['old_allocation', 'new_allocation', 'old_additional_allocations', 'new_additional_allocations']);
            });

            Schema::table('servers', function (Blueprint $table) {
                $table->json('ports')->nullable();
            });

            DB::table('servers')->update(['ports' => '[]']);

            Schema::table('servers', function (Blueprint $table) {
                $table->json('ports')->change();
            });

            $portMappings = [];
            foreach (DB::table('allocations')->get() as $allocation) {
                $portMappings[$allocation->server_id][] = "$allocation->ip:$allocation->port";
            }

            foreach ($portMappings as $serverId => $ports) {
                DB::table('servers')
                    ->where('id', $serverId)
                    ->update(['ports' => json_encode($ports)]);
            }

            Schema::table('servers', function (Blueprint $table) {
                $table->dropUnique(['allocation_id']);
                $table->dropColumn(['allocation_id']);
            });

            Schema::dropIfExists('allocations');

            Schema::table('nodes', function (Blueprint $table) {
                $table->boolean('strict_ports')->default(true);
            });
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

        Schema::table('server_transfers', function (Blueprint $table) {
            $table->integer('old_node');
            $table->integer('new_node');
            $table->json('old_additional_allocations')->nullable();
            $table->json('new_additional_allocations')->nullable();
        });
    }
};
