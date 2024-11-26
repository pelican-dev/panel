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
        Schema::table('password_resets', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                if (!Schema::hasColumn('password_resets', 'id')) {
                    $table->id()->first();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_resets', function (Blueprint $table) {
            if (Schema::hasColumn('password_resets', 'id')) {
                $table->dropColumn('id');
            }
        });
    }
};
