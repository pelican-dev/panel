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
        Schema::table('egg_variables', function (Blueprint $table) {
            $table->unique(['egg_id', 'env_variable']);
            $table->unique(['egg_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egg_variables', function (Blueprint $table) {
            $table->dropUnique(['egg_id', 'env_variable']);
            $table->dropUnique(['egg_id', 'name']);
        });
    }
};
