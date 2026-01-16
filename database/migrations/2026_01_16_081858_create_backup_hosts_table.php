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
        Schema::create('backup_hosts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('schema');
            $table->json('configuration')->nullable();
            $table->timestamps();
        });

        Schema::create('backup_host_node', function (Blueprint $table) {
            $table->unsignedInteger('node_id');
            $table->foreign('node_id')->references('id')->on('nodes')->cascadeOnDelete();

            $table->unsignedInteger('backup_host_id');
            $table->foreign('backup_host_id')->references('id')->on('backup_hosts')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['node_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_hosts');

        Schema::dropIfExists('backup_host_node');
    }
};
