<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alert_banners', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable();
            $table->string('color')->default('blue'); // Default to blue
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('alert_banners');
    }
};
