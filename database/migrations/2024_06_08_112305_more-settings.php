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
        //
    }
};
