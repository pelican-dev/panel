<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        switch (Schema::getConnection()->getDriverName()) {
            case 'pgsql':
                $database = DB::connection()->getDatabaseName();
                DB::statement(sprintf('REVOKE CONNECT ON DATABASE "%s" FROM public', $database));
                break;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        switch (Schema::getConnection()->getDriverName()) {
            case 'pgsql':
                $database = DB::connection()->getDatabaseName();
                DB::statement(sprintf('GRANT CONNECT ON DATABASE "%s" TO public', $database));
                break;
        }
    }
};
