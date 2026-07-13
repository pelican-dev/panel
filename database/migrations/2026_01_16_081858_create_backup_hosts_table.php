<?php

use App\Models\BackupHost;
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
        if (!Schema::hasTable('backup_hosts')) {
            Schema::create('backup_hosts', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('schema');
                $table->json('configuration')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('backup_host_node')) {
            Schema::create('backup_host_node', function (Blueprint $table) {
                $table->unsignedInteger('node_id');
                $table->foreign('node_id')->references('id')->on('nodes')->cascadeOnDelete();

                $table->unsignedInteger('backup_host_id');
                $table->foreign('backup_host_id')->references('id')->on('backup_hosts')->cascadeOnDelete();

                $table->timestamps();

                $table->unique(['node_id']);
            });
        }

        if (!Schema::hasColumn('backups', 'backup_host_id')) {
            Schema::table('backups', function (Blueprint $table) {
                $table->unsignedInteger('backup_host_id')->nullable()->after('disk');
            });
        }

        $backupHost = BackupHost::query()->first() ?? BackupHost::create([
            'name' => $this->oldBackupDriver() === 's3' ? 'Remote' : 'Local',
            'schema' => $this->oldBackupDriver(),
            'configuration' => $this->oldBackupConfiguration(),
        ]);

        DB::table('backups')
            ->whereNull('backup_host_id')
            ->orWhere('backup_host_id', 0)
            ->update(['backup_host_id' => $backupHost->id]);

        if ($this->columnIsNullable('backups', 'backup_host_id')) {
            DB::statement('ALTER TABLE `backups` MODIFY `backup_host_id` INT UNSIGNED NOT NULL');
        }

        if (!$this->foreignKeyExists('backups', 'backups_backup_host_id_foreign')) {
            Schema::table('backups', function (Blueprint $table) {
                $table->foreign('backup_host_id')->references('id')->on('backup_hosts');
            });
        }

        if (Schema::hasColumn('backups', 'disk')) {
            Schema::table('backups', function (Blueprint $table) {
                $table->dropColumn('disk');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('backups', 'backup_host_id')) {
            Schema::table('backups', function (Blueprint $table) {
                if (!Schema::hasColumn('backups', 'disk')) {
                    $table->string('disk')->after('backup_host_id');
                }

                if ($this->foreignKeyExists('backups', 'backups_backup_host_id_foreign')) {
                    $table->dropForeign(['backup_host_id']);
                }

                $table->dropColumn('backup_host_id');
            });
        }

        Schema::dropIfExists('backup_host_node');

        Schema::dropIfExists('backup_hosts');
    }

    private function oldBackupDriver(): string
    {
        return env('APP_BACKUP_DRIVER', 'wings');
    }

    /**
     * @return array<string, mixed>|null
     */
    private function oldBackupConfiguration(): ?array
    {
        if ($this->oldBackupDriver() !== 's3') {
            return null;
        }

        return [
            'region' => env('AWS_DEFAULT_REGION'),
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'bucket' => env('AWS_BACKUPS_BUCKET'),
            'prefix' => env('AWS_BACKUPS_BUCKET', ''),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'use_accelerate_endpoint' => env('AWS_BACKUPS_USE_ACCELERATE', false),
            'storage_class' => env('AWS_BACKUPS_STORAGE_CLASS'),
        ];
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return false;
        }

        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }

    private function columnIsNullable(string $table, string $column): bool
    {
        if (DB::getDriverName() !== 'mysql') {
            return false;
        }

        return DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('COLUMN_NAME', $column)
            ->where('IS_NULLABLE', 'YES')
            ->exists();
    }
};
