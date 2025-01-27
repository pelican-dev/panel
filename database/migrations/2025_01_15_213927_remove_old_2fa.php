<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('use_totp', 'totp_secret', 'totp_authenticated_at');
        });
    }

    public function down(): void
    {
        // Point of no return...
    }
};
