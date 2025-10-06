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
        Schema::table('servers', function (Blueprint $table) {
            $table->string('image')->nullable()->after('daemonSecret');
        });

        // Populate the column
        DB::transaction(function () {
            $servers = DB::table('servers')->select(
                'servers.id',
                'service_options.docker_image as s_optionImage'
            )->join('service_options', 'service_options.id', '=', 'servers.option')->get();

            foreach ($servers as $server) {
                $server->image = $server->s_optionImage;
                $server->save();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
