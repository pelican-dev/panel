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
        Schema::dropIfExists('node_configuration_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('node_configuration_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 32);
            $table->unsignedInteger('node_id');
            $table->timestamps();
        });

        Schema::table('node_configuration_tokens', function (Blueprint $table) {
            $table->foreign('node_id')->references('id')->on('nodes');
        });
    }
};
