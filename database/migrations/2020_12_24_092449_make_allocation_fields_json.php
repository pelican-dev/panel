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
        switch (Schema::getConnection()->getDriverName()) {
            case 'sqlite':
            case 'mysql':
            case 'mariadb':
                Schema::table('server_transfers', function (Blueprint $table) {
                    $table->json('old_additional_allocations')->nullable()->change();
                    $table->json('new_additional_allocations')->nullable()->change();
                });
                break;
            case 'pgsql':
                DB::statement('ALTER TABLE server_transfers ALTER COLUMN old_additional_allocations TYPE jsonb USING to_jsonb(old_additional_allocations)');
                DB::statement('ALTER TABLE server_transfers ALTER COLUMN new_additional_allocations TYPE jsonb USING to_jsonb(new_additional_allocations)');
                break;
        }
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
