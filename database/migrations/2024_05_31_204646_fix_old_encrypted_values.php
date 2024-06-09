<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $keys = DB::table('api_keys')->get();
        foreach ($keys as $key) {
            try {
                $reEncrypted = encrypt(decrypt($key->token), false);
                DB::table('api_keys')
                    ->where('id', $key->id)
                    ->update(['token' => $reEncrypted]);
            } catch (Exception $exception) {
                logger()->error($exception->getMessage());
            }
        }

        $databases = DB::table('databases')->get();
        foreach ($databases as $database) {
            try {
                $reEncrypted = encrypt(decrypt($database->password), false);
                DB::table('databases')
                    ->where('id', $database->id)
                    ->update(['password' => $reEncrypted]);
            } catch (Exception $exception) {
                logger()->error($exception->getMessage());
            }
        }

        $databaseHosts = DB::table('database_hosts')->get();
        foreach ($databaseHosts as $host) {
            try {
                $reEncrypted = encrypt(decrypt($host->password), false);
                DB::table('database_hosts')
                    ->where('id', $host->id)
                    ->update(['password' => $reEncrypted]);
            } catch (Exception $exception) {
                logger()->error($exception->getMessage());
            }
        }

        $nodes = DB::table('nodes')->get();
        foreach ($nodes as $node) {
            try {
                $reEncrypted = encrypt(decrypt($node->daemon_token), false);
                DB::table('nodes')
                    ->where('id', $node->id)
                    ->update(['daemon_token' => $reEncrypted]);
            } catch (Exception $exception) {
                logger()->error($exception->getMessage());
            }
        }

        $users = DB::table('users')->get();
        foreach ($users as $user) {
            try {
                $reEncrypted = encrypt(decrypt($user->totp_secret), false);
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['totp_secret' => $reEncrypted]);
            } catch (Exception $exception) {
                logger()->error($exception->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to do anything
    }
};
