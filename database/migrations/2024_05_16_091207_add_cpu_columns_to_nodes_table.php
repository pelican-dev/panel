<?php

use Illuminate\Support\Facades\DB;
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
        Schema::table('nodes', function (Blueprint $table) {
            $table->integer('cpu')->unsigned()->after('disk_overallocate');
            $table->integer('cpu_overallocate')->default(0)->after('cpu');
        });

        DB::table('nodes')->update(['cpu' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn('cpu');
            $table->dropColumn('cpu_overallocate');
        });
    }
};
