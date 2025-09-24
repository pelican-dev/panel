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
        Schema::table('nodes', function (Blueprint $table) {
            $table->string('daemonBase', 191)->default(null)->change();
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->renameColumn('daemonListen', 'daemon_listen');
            $table->renameColumn('daemonBase', 'daemon_base');
            $table->renameColumn('daemonSFTP', 'daemon_sftp');
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->renameColumn('uuidShort', 'uuid_short');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->renameColumn('daemon_listen', 'daemonListen');
            $table->renameColumn('daemon_sftp', 'daemonSFTP');
            $table->renameColumn('daemon_base', 'daemonBase');
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->renameColumn('uuid_short', 'uuidShort');
        });
    }
};
