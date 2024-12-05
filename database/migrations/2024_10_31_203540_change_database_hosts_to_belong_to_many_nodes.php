<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('database_host_node', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained('nodes', 'id');
            $table->foreignId('database_host_id')->constrained('database_hosts', 'id');
            $table->timestamps();
        });

        $databaseNodes = DB::table('database_hosts')->whereNotNull('node_id')->get();
        $newJoinEntries = $databaseNodes->map(fn ($record) => [
            'node_id' => $record->node_id,
            'database_host_id' => $record->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        DB::table('database_host_node')->insert($newJoinEntries->toArray());

        Schema::table('database_hosts', function (Blueprint $table) {
            $table->dropForeign(['node_id']);
            $table->dropColumn('node_id');
        });
    }

    public function down(): void
    {
        Schema::table('database_hosts', function (Blueprint $table) {
            $table->foreignId('node_id')->nullable()->references('id')->on('nodes');
        });

        foreach (DB::table('database_host_node')->get() as $record) {
            DB::table('database_hosts')
                ->where('id', $record->database_host_id)
                ->update(['node_id' => $record->node_id]);
        }

        Schema::drop('database_host_node');
    }
};
