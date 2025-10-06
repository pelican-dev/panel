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
        Schema::table('server_transfers', function (Blueprint $table) {
            $table->boolean('archived')->default(0)->after('new_additional_allocations');
        });

        // Update archived to all be true on existing transfers.
        Schema::table('server_transfers', function (Blueprint $table) {
            DB::table('server_transfers')->where('successful', 1)->update(['archived' => 1]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_transfers', function (Blueprint $table) {
            $table->dropColumn('archived');
        });
    }
};
