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
        Schema::create('databases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('server')->unsigned();
            $table->integer('db_server')->unsigned();
            $table->string('database')->unique();
            $table->string('username')->unique();
            $table->string('remote')->default('%');
            $table->text('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('databases');
    }
};
