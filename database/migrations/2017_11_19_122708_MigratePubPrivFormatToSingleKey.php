<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
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
        DB::transaction(function () {
            DB::table('api_keys')->get()->each(function ($item) {
                try {
                    $decrypted = Crypt::decrypt($item->secret);
                } catch (DecryptException $exception) {
                    $decrypted = Str::random(32);
                } finally {
                    DB::table('api_keys')->where('id', $item->id)->update([
                        'secret' => $decrypted,
                    ]);
                }
            });
        });

        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            Schema::table('api_keys', function (Blueprint $table) {
                $table->dropColumn('public');
                $table->string('secret', 32)->change();
                $table->renameColumn('secret', 'token');
                $table->string('token', 32)->unique()->change();
            });
        } elseif (Schema::getConnection()->getDriverName() === 'pgsql') {
            // Rename column 'secret' to 'token'
            DB::statement('ALTER TABLE api_keys RENAME COLUMN secret TO token');

            // Change data type of 'token' to CHAR(32) and set NOT NULL constraint
            DB::statement('ALTER TABLE api_keys ALTER COLUMN token TYPE CHAR(32)');
            DB::statement('ALTER TABLE api_keys ALTER COLUMN token SET NOT NULL');

            // Add unique constraint on 'token'
            DB::statement('ALTER TABLE api_keys ADD CONSTRAINT api_keys_token_unique UNIQUE (token)');

        } else {
            DB::statement('ALTER TABLE `api_keys` CHANGE `secret` `token` CHAR(32) NOT NULL, ADD UNIQUE INDEX `api_keys_token_unique` (`token`(32))');
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `api_keys` CHANGE `token` `secret` TEXT, DROP INDEX `api_keys_token_unique`');

        Schema::table('api_keys', function (Blueprint $table) {
            $table->string('public', 16)->after('user_id');
        });

        DB::transaction(function () {
            DB::table('api_keys')->get()->each(function ($item) {
                DB::table('api_keys')->where('id', $item->id)->update([
                    'public' => Str::random(16),
                    'secret' => Crypt::encrypt($item->secret),
                ]);
            });
        });
    }
};
