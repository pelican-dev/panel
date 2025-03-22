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
        DB::table('egg_variables')->select(['id', 'rules'])->cursor()->each(function ($eggVariable) {
            DB::table('egg_variables')->where('id', $eggVariable->id)->update(['rules' => explode('|', $eggVariable->rules)]);
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE egg_variables ALTER COLUMN rules TYPE JSON USING rules::json');

            return;
        }

        Schema::table('egg_variables', function (Blueprint $table) {
            $table->json('rules')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egg_variables', function (Blueprint $table) {
            $table->text('rules')->change();
        });

        DB::table('egg_variables')->select(['id', 'rules'])->cursor()->each(function ($eggVariable) {
            DB::table('egg_variables')->where('id', $eggVariable->id)->update(['rules' => implode('|', json_decode($eggVariable->rules))]);
        });
    }
};
