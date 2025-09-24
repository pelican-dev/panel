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
        Schema::table('services', function (Blueprint $table) {
            $table->renameColumn('file', 'folder');
            $table->dropColumn('executable');
            $table->text('description')->nullable()->change();
            $table->text('startup')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('executable')->after('folder');
            $table->renameColumn('folder', 'file');
            $table->text('description')->nullable(false)->change();
            $table->text('startup')->nullable(false)->change();
        });
    }
};
