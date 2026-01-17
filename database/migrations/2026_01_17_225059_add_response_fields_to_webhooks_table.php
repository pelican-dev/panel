<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webhooks', function (Blueprint $table) {
            $table->integer('response_code')->nullable()->after('successful_at');
            $table->text('response')->nullable()->after('response_code');
            $table->text('error')->nullable()->after('response');
        });
    }

    public function down(): void
    {
        Schema::table('webhooks', function (Blueprint $table) {
            $table->dropColumn(['response_code', 'response', 'error']);
        });
    }
};
