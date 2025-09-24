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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 36)->unique();
            $table->string('email')->unique();
            $table->text('password');
            $table->string('remember_token')->nullable();
            $table->string('language', 5)->default('en');
            $table->tinyInteger('root_admin')->unsigned()->default(0);
            $table->tinyInteger('use_totp')->unsigned();
            $table->string('totp_secret', 16)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
