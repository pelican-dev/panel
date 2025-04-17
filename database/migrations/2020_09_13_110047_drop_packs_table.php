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
        Schema::dropIfExists('packs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('packs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('egg_id');
            $table->string('uuid', 36)->unique();
            $table->string('name');
            $table->string('version');
            $table->text('description')->nullable();
            $table->tinyInteger('selectable')->default(1);
            $table->tinyInteger('visible')->default(1);
            $table->tinyInteger('locked')->default(0);
            $table->timestamps();
        });

        Schema::table('packs', function (Blueprint $table) {
            $table->foreign('egg_id')->references('id')->on('eggs')->cascadeOnDelete();
        });
    }
};
