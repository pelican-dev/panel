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
        Schema::create('service_packs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('option');
            $table->string('uuid', 36)->unique();
            $table->string('name');
            $table->string('version');
            $table->text('description')->nullable();
            $table->boolean('selectable')->default(true);
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->foreign('option')->references('id')->on('service_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('service_packs');
    }
};
