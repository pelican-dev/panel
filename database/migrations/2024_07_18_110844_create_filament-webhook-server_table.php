<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('filament_webhook_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->string('url');
            $table->string('method');
            $table->string('model');
            $table->json('header')->nullable();
            $table->string('data_option');
            $table->boolean('verifySsl');
            $table->string('status')->nullable();
            $table->json('events')->nullable();
            $table->timestamps();
        });
    }
};
