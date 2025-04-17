<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('node_configuration_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 32);
            $table->timestamp('expires_at');
            $table->integer('node')->unsigned();
            $table->foreign('node')->references('id')->on('nodes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_configuration_tokens');
    }
};
