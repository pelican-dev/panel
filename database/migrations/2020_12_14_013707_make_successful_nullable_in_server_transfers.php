<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        switch (Schema::getConnection()->getDriverName()) {
            case 'sqlite':
            case 'mysql':
            case 'mariadb':
                Schema::table('server_transfers', function (Blueprint $table) {
                    $table->boolean('successful')->nullable()->default(null)->change();
                });
                break;
            case 'pgsql':
                DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful TYPE boolean USING (successful::int::boolean), ALTER COLUMN successful DROP NOT NULL, ALTER COLUMN successful DROP DEFAULT, ALTER COLUMN successful DROP IDENTITY IF EXISTS');
                break;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_transfers', function (Blueprint $table) {
            $table->boolean('successful')->default(0)->change();
        });
    }
};
