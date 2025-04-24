<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $rootAdmins = User::all()->filter(fn ($user) => $user->isRootAdmin());
        foreach ($rootAdmins as $rootAdmin) {
            $rootAdmin->syncRoles(Role::getRootAdmin());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No going back
    }
};
