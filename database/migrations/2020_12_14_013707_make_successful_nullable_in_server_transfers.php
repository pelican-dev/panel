<?php

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
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            // Drop existing default first
            DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful DROP DEFAULT');

            // Change type to boolean explicitly casting smallint values
            DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful TYPE BOOLEAN USING (successful <> 0)');

            // Set column nullable if desired
            DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful DROP NOT NULL');

            return;
        }

        Schema::table('server_transfers', function (Blueprint $table) {
            $table->boolean('successful')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            // Convert boolean back to smallint
            DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful TYPE SMALLINT USING (CASE WHEN successful THEN 1 ELSE 0 END)');

            // Restore previous defaults and constraints
            DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful SET DEFAULT 0');
            DB::statement('ALTER TABLE server_transfers ALTER COLUMN successful SET NOT NULL');

            return;
        }

        Schema::table('server_transfers', function (Blueprint $table) {
            $table->boolean('successful')->default(0)->change();
        });
    }
};
