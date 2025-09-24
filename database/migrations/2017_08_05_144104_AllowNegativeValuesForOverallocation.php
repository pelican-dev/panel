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
        Schema::table('nodes', function (Blueprint $table) {
            $table->integer('disk_overallocate')->default(0)->nullable(false)->change();
            $table->integer('memory_overallocate')->default(0)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->unsignedMediumInteger('disk_overallocate')->nullable();
            $table->unsignedMediumInteger('memory_overallocate')->nullable();
        });
    }
};
