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
        Schema::dropIfExists('downloads');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 36)->unique();
            $table->string('server', 36);
            $table->text('path');
            $table->timestamps();
        });
    }
};
