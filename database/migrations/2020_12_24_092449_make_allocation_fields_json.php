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
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            Schema::table('server_transfers', function (Blueprint $table) {
                DB::statement('ALTER TABLE server_transfers ALTER COLUMN old_additional_allocations TYPE JSON USING old_additional_allocations::json');
                DB::statement('ALTER TABLE server_transfers ALTER COLUMN new_additional_allocations TYPE JSON USING new_additional_allocations::json');
            });

            return;
        }

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
