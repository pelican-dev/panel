<?php

use App\Models\Setting;
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
        Schema::dropIfExists('settings');

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('label');
            $table->text('value')->nullable();
            $table->json('attributes')->nullable();
            $table->string('type');
            $table->longText('description');
            $table->timestamps();
        });

        Setting::create([
            'key' => 'APP_NAME',
            'label' => 'Panel Name',
            'value' => 'Pelican',
            'type' => 'text',
            'description' => 'This is the name that is used throughout the panel and in emails sent to clients.',
        ]);

        Setting::create([
            'key' => 'FILAMENT_TOP_NAVIGATION',
            'label' => 'Topbar',
            'value' => 'false',
            'type' => 'select',
            'attributes' => [
                'options' => [
                    'false' => 'False',
                    'true' => 'True',
                ],
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });
    }
};
