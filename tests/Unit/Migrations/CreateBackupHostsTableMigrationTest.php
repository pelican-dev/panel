<?php

namespace App\Tests\Unit\Migrations;

use App\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBackupHostsTableMigrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('backup_host_node');
        Schema::dropIfExists('backup_hosts');
        Schema::dropIfExists('backups');
        Schema::dropIfExists('nodes');
        Schema::enableForeignKeyConstraints();
    }

    public function test_existing_backups_are_assigned_to_created_backup_host(): void
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('disk');
        });

        DB::table('backups')->insert([
            ['disk' => 'local'],
            ['disk' => 'local'],
        ]);

        $migration = require database_path('migrations/2026_01_16_081858_create_backup_hosts_table.php');
        $migration->up();

        $backupHostId = DB::table('backup_hosts')->value('id');

        $this->assertNotNull($backupHostId);
        $this->assertSame(2, DB::table('backups')->where('backup_host_id', $backupHostId)->count());
        $this->assertFalse(Schema::hasColumn('backups', 'disk'));
    }
}
