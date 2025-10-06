<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     *
     * @throws \Exception
     */
    public function up(): void
    {
        Schema::table('nodes', function (Blueprint $table) {
            $table->dropUnique(['daemonSecret']);
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->string('uuid', 36)->nullable()->after('id');
            $table->string('daemon_token_id', 16)->nullable()->after('upload_size');
            $table->renameColumn('daemonSecret', 'daemon_token');
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->text('daemon_token')->change();
        });

        foreach (DB::select('SELECT id, daemon_token FROM nodes') as $datum) {
            DB::update('UPDATE nodes SET uuid = ?, daemon_token_id = ?, daemon_token = ? WHERE id = ?', [
                Uuid::uuid4()->toString(),
                substr($datum->daemon_token, 0, 16),
                encrypt(substr($datum->daemon_token, 16)),
                $datum->id,
            ]);
        }

        Schema::table('nodes', function (Blueprint $table) {
            $table->unique(['uuid']);
            $table->unique(['daemon_token_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            foreach (DB::select('SELECT id, daemon_token_id, daemon_token FROM nodes') as $datum) {
                DB::update('UPDATE nodes SET daemon_token = ? WHERE id = ?', [
                    $datum->daemon_token_id . decrypt($datum->daemon_token),
                    $datum->id,
                ]);
            }
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropUnique(['daemon_token_id']);
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropColumn(['uuid', 'daemon_token_id']);
            $table->renameColumn('daemon_token', 'daemonSecret');
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->string('daemonSecret', 36)->change();
            $table->unique(['daemonSecret']);
        });
    }
};
