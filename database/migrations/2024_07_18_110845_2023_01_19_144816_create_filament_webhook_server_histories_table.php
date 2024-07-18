<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('filament_webhook_server_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_client')->nullable();
            $table->uuid('uuid');
            $table->string('status_code')->nullable();
            $table->text('errorMessage')->nullable();
            $table->string('errorType')->nullable();
            $table->integer('attempt')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('filament_webhook_server_histories');
    }
};
