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
        Schema::create('server_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumInteger('server_id')->unsigned();
            $table->mediumInteger('variable_id')->unsigned();
            $table->string('variable_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_variables');
    }
};
