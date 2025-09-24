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
        Schema::table('servers', function (Blueprint $table) {
            $table->string('status')->nullable()->after('description');
        });

        DB::transaction(function () {
            DB::table('servers')->where('suspended', 1)->update(['status' => 'suspended']);
            DB::table('servers')->where('suspended', 0)->update(['status' => 'installing']);
            DB::table('servers')->where('suspended', 2)->update(['status' => 'install_failed']);
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('suspended');
            $table->dropColumn('installed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->unsignedTinyInteger('suspended')->default(0);
            $table->unsignedTinyInteger('installed')->default(0);
        });

        DB::transaction(function () {
            DB::update('UPDATE servers SET `suspended` = 1 WHERE `status` = \'suspended\'');
            DB::update('UPDATE servers SET `installed` = 1 WHERE `status` IS NULL');
            DB::update('UPDATE servers SET `installed` = 2 WHERE `status` = \'install_failed\'');
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
