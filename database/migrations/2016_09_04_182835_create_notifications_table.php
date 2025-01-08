<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            switch (Schema::getConnection()->getDriverName()) {
                case 'sqlite':
                case 'mysql':
                case 'mariadb':
                    $table->text('data');
                    break;
                case 'pgsql':
                    $table->jsonb('data');
                    break;
            }
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('notifications');
    }
};
