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
        DB::table('subusers')
            ->whereIn('user_id', function ($query) {
                $query->select('id')
                    ->from('servers')
                    ->whereColumn('owner_id', 'subusers.server_id');
            })
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed
    }
};
