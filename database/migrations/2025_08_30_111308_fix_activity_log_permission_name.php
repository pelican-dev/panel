<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Permission::where('name', 'seeIps activity')->update(['name' => 'seeIps activityLog']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::where('name', 'seeIps activityLog')->update(['name' => 'seeIps activity']);
    }
};
