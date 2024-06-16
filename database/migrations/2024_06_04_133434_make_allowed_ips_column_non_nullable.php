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
        DB::table('api_keys')->whereNull('allowed_ips')->update([
            'allowed_ips' => '[]',
        ]);

        Schema::table('api_keys', function (Blueprint $table) {
            $table->text('allowed_ips')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_keys', function (Blueprint $table) {
            $table->text('allowed_ips')->nullable()->change();
        });
    }
};
