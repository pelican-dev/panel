<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            switch (Schema::getConnection()->getDriverName()) {
                case 'mysql':
                case 'mariadb':
                case 'sqlite':
                    $table->json('oauth')->nullable()->after('totp_authenticated_at');
                    break;
                case 'pgsql':
                    $table->jsonb('oauth')->nullable()->after('totp_authenticated_at');
                    break;
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('oauth');
        });
    }
};
