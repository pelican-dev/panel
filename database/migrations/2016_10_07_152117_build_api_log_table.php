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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('authorized');
            $table->text('error')->nullable();
            $table->string('key', 16)->nullable();
            $table->string('method', 6);
            $table->text('route');
            $table->text('content')->nullable();
            $table->text('user_agent');
            $table->ipAddress('request_ip');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('api_logs');
    }
};
