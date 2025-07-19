<?php

use App\Models\ActivityLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $logs = ActivityLog::where('event', 'auth:fail')->get();
            foreach ($logs as $log) {
                $log->update(['properties' => collect($log->properties)->except(['password'])->toArray()]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not needed
    }
};
