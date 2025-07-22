<?php

use App\Models\User;
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
        DB::transaction(function () {
            Schema::table('users', function (Blueprint $table) {
                $table->text('mfa_app_secret')->nullable();
                $table->text('mfa_app_recovery_codes')->nullable();
                $table->boolean('mfa_email_enabled')->default(false);
            });

            $users = User::all();
            foreach ($users as $user) {
                $user->update([
                    'mfa_app_secret' => $user->use_totp ? $user->totp_secret : null,
                    'mfa_app_recovery_codes' => null,
                    'mfa_email_enabled' => false,
                ]);
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('use_totp');
                $table->dropColumn('totp_secret');
                $table->dropColumn('totp_authenticated_at');
            });

            Schema::dropIfExists('recovery_tokens');
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
