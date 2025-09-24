<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('public')->unsigned();
            $table->string('name');
            $table->mediumInteger('location')->unsigned();
            $table->string('fqdn');
            $table->string('scheme')->default('https');
            $table->integer('memory')->unsigned();
            $table->mediumInteger('memory_overallocate')->unsigned()->nullable();
            $table->integer('disk')->unsigned();
            $table->mediumInteger('disk_overallocate')->unsigned()->nullable();
            $table->string('daemonSecret', 36)->unique();
            $table->smallInteger('daemonListen')->unsigned()->default(8080);
            $table->smallInteger('daemonSFTP')->unsgined()->default(2022);
            $table->string('daemonBase')->default('/home/daemon-files');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
