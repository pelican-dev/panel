<?php

use App\Contracts\Repository\DaemonKeyRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $inserts = [];

        $servers = DB::table('servers')->select('id', 'owner_id')->get();
        $servers->each(function ($server) use (&$inserts) {
            $inserts[] = [
                'user_id' => $server->owner_id,
                'server_id' => $server->id,
                'secret' => DaemonKeyRepositoryInterface::INTERNAL_KEY_IDENTIFIER . Str::random(40),
                'expires_at' => Carbon::now()->addMinutes(config('panel.api.key_expire_time', 720))->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        });

        DB::transaction(function () use ($inserts) {
            DB::table('daemon_keys')->insert($inserts);
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->dropUnique(['daemonSecret']);
            $table->dropColumn('daemonSecret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->string('daemonSecret', 36)->after('startup')->unique();
        });

        DB::table('daemon_keys')->truncate();
    }
};
