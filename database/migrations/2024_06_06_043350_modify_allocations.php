<?php

use App\Models\Objects\Endpoint;
use App\Models\Server;
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
        Schema::table('server_transfers', function (Blueprint $table) {
            $table->dropColumn(['old_allocation', 'new_allocation', 'old_additional_allocations', 'new_additional_allocations']);
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->json('ports')->nullable();
        });

        $portMappings = [];
        foreach (DB::table('allocations')->get() as $allocation) {
            $portMappings[$allocation->server_id][] = "$allocation->ip:$allocation->port";
        }

        foreach ($portMappings as $serverId => $ports) {
            /** @var Server $server */
            $server = Server::find($serverId);
            if (!$server) {
                // Orphaned Allocations

                continue;
            }

            foreach ($ports as $port) {
                $server->ports ??= collect();
                $server->ports->add(new Endpoint($port));
            }
            $server->save();
        }

        try {
            Schema::table('servers', function (Blueprint $table) {
                $table->dropForeign(['allocation_id']);
            });
        } catch (Throwable) {
            // pass for databases that don't support this like sqlite
        }

        Schema::table('servers', function (Blueprint $table) {
            $table->dropUnique(['allocation_id']);
            $table->dropColumn(['allocation_id']);
        });

        Schema::dropIfExists('allocations');

        Schema::table('nodes', function (Blueprint $table) {
            $table->boolean('strict_ports')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Too much time to ensure this works correctly, please take a backup if necessary
    }
};
