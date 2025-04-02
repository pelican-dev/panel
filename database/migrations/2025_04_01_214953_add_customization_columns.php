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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dashboard_layout')->default('grid');
            $table->integer('console_font_size')->default(14);
            $table->string('console_font')->default('monospace');
            $table->integer('console_rows')->default(30);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dashboard_layout');
            $table->dropColumn('console_font_size');
            $table->dropColumn('console_font');
            $table->dropColumn('console_rows');
        });
    }
};
