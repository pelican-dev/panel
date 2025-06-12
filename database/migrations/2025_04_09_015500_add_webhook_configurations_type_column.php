<?php

use App\Enums\WebhookType;
use App\Models\WebhookConfiguration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('webhook_configurations', function (Blueprint $table) {
            $table->string('type')->nullable()->after('id');
            $table->json('payload')->nullable()->after('type');
        });

        foreach (WebhookConfiguration::all() as $webhookConfig) {
            $type = str($webhookConfig->endpoint)->contains('discord.com') ? WebhookType::Discord->value : WebhookType::Regular->value;

            DB::table('webhook_configurations')
                ->where('id', $webhookConfig->id)
                ->update(['type' => $type]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webhook_configurations', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('payload');
        });
    }
};
