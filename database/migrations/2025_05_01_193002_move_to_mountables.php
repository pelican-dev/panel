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
        Schema::create('mountables', function (Blueprint $table) {
            $table->unsignedInteger('mount_id');

            $table->string('mountable_type');
            $table->unsignedBigInteger('mountable_id');
            $table->index(['mountable_id', 'mountable_type'], 'mountables_mountable_id_mountable_type_index');

            $table->foreign('mount_id')
                ->references('id') // mount id
                ->on('mounts')
                ->onDelete('cascade');

            $table->primary(['mount_id', 'mountable_id', 'mountable_type'],
                'mountables_mountable_type_primary');
        });

        Schema::table('mount_node', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropForeign(['mount_id']);
            $table->dropUnique(['node_id', 'mount_id']);
        });

        $inserts = [];
        $nodeMounts = DB::table('mount_node')->get();
        $nodeMounts->each(function ($mount) use (&$inserts) {
            $inserts[] = [
                'mount_id' => $mount->mount_id,
                'mountable_type' => 'node',
                'mountable_id' => $mount->node_id,
            ];
        });

        Schema::table('mount_server', function (Blueprint $table) {
            $table->dropForeign(['server_id']);
            $table->dropForeign(['mount_id']);
            $table->dropUnique(['server_id', 'mount_id']);
        });

        $serverMounts = DB::table('mount_server')->get();
        $serverMounts->each(function ($mount) use (&$inserts) {
            $inserts[] = [
                'mount_id' => $mount->mount_id,
                'mountable_type' => 'server',
                'mountable_id' => $mount->server_id,
            ];
        });

        Schema::table('egg_mount', function (Blueprint $table) {
            $table->dropForeign(['egg_id']);
            $table->dropForeign(['mount_id']);
            $table->dropUnique(['egg_id', 'mount_id']);
        });

        $eggMounts = DB::table('egg_mount')->get();
        $eggMounts->each(function ($mount) use (&$inserts) {
            $inserts[] = [
                'mount_id' => $mount->mount_id,
                'mountable_type' => 'egg',
                'mountable_id' => $mount->egg_id,
            ];
        });

        DB::transaction(function () use ($inserts) {
            DB::table('mountables')->insert($inserts);
        });

        Schema::drop('mount_node');
        Schema::drop('mount_server');
        Schema::drop('egg_mount');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed
    }
};
