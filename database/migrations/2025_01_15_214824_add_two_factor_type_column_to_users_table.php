<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Vormkracht10\TwoFactorAuth\Enums\TwoFactorType;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'two_factor_type') && Schema::hasColumn('users', 'two_factor_recovery_codes')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('two_factor_type', TwoFactorType::names())->nullable()->after('two_factor_recovery_codes');
            });
        }
    }
};
