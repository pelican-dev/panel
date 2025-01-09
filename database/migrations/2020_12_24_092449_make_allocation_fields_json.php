<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('server_transfers', function (Blueprint $table) {
            $table->json('old_additional_allocations')->nullable()->change();
            $table->json('new_additional_allocations')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_transfers', function (Blueprint $table) {
            $table->string('old_additional_allocations')->nullable()->change();
            $table->string('new_additional_allocations')->nullable()->change();
        });
    }
};
