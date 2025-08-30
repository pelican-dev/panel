<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('permissions')
            ->where('name', 'seeIps activity')
            ->update(['name' => 'seeIps activityLog']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('permissions')
            ->where('name', 'seeIps activityLog')
            ->update(['name' => 'seeIps activity']);
    }
};
