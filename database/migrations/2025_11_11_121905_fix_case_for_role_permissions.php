<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $allPermissions = [];

            foreach (Role::getPermissionList() as $model => $permissions) {
                foreach ($permissions as $permission) {
                    $allPermissions[] = $permission . ' ' . $model;
                }
            }

            foreach (Permission::all() as $spatiePermission) {
                $name = $spatiePermission->name;

                foreach ($allPermissions as $permission) {
                    if (Str::lower($name) === Str::lower($permission)) {
                        $name = $permission;
                        break;
                    }
                }

                $spatiePermission->update(['name' => $name]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed
    }
};
