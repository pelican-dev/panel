<?php

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
        Schema::table('activity_log_subjects', function (Blueprint $table) {
            $table->string('subject_type')->change();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('event')->change();
            $table->string('ip')->change();
            $table->string('actor_type')->nullable()->default(null)->change();
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->string('ip')->change();
            $table->string('notes')->nullable()->default(null)->change();
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('action')->change();
            $table->string('subaction')->nullable()->default(null)->change();
        });

        Schema::table('backups', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('disk')->change();
            $table->string('checksum')->nullable()->default(null)->change();
        });

        Schema::table('database_hosts', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('host')->change();
            $table->string('username')->change();
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->string('database')->change();
            $table->string('username')->change();
            $table->string('remote')->default('%')->change();
        });

        Schema::table('egg_variables', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('env_variable')->change();
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->string('author')->change();
            $table->string('name')->change();
            $table->string('config_stop')->nullable()->default(null)->change();
            $table->string('script_container')->default('alpine:3.4')->change();
            $table->string('script_entry')->default('ash')->change();
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->nullable()->default(null)->change();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('queue')->change();
        });

        Schema::table('migrations', function (Blueprint $table) {
            $table->string('migration')->change();
        });

        Schema::table('mounts', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('source')->change();
            $table->string('target')->change();
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('fqdn')->change();
            $table->string('scheme')->default('https')->change();
            $table->string('daemon_sftp_alias')->nullable()->default(null)->change();
            $table->string('daemon_base')->change();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->string('id')->change();
            $table->string('type')->change();
            $table->string('notifiable_type')->change();
        });

        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('email')->change();
            $table->string('token')->change();
        });

        Schema::table('recovery_tokens', function (Blueprint $table) {
            $table->string('token')->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('cron_day_of_week')->change();
            $table->string('cron_month')->change();
            $table->string('cron_day_of_month')->change();
            $table->string('cron_hour')->change();
            $table->string('cron_minute')->change();
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->string('external_id')->nullable()->default(null)->change();
            $table->string('name')->change();
            $table->string('status')->nullable()->default(null)->change();
            $table->string('threads')->nullable()->default(null)->change();
            $table->string('image')->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id')->change();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('key')->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('action')->change();
        });

        Schema::table('user_ssh_keys', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('fingerprint')->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('external_id')->nullable()->default(null)->change();
            $table->string('username')->change();
            $table->string('email')->change();
            $table->string('name_first')->nullable()->default(null)->change();
            $table->string('name_last')->nullable()->default(null)->change();
            $table->string('remember_token')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_log_subjects', function (Blueprint $table) {
            $table->string('subject_type', 191)->change();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('event', 191)->change();
            $table->string('ip', 191)->change();
            $table->string('actor_type', 191)->nullable()->default(null)->change();
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->string('ip', 191)->change();
            $table->string('notes', 191)->nullable()->default(null)->change();
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('action', 191)->change();
            $table->string('subaction', 191)->nullable()->default(null)->change();
        });

        Schema::table('backups', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('disk', 191)->change();
            $table->string('checksum', 191)->nullable()->default(null)->change();
        });
        Schema::table('database_hosts', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('host', 191)->change();
            $table->string('username', 191)->change();
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->string('database', 191)->change();
            $table->string('username', 191)->change();
            $table->string('remote', 191)->default('%', 191)->change();
        });

        Schema::table('egg_variables', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('env_variable', 191)->change();
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->string('author', 191)->change();
            $table->string('name', 191)->change();
            $table->string('config_stop', 191)->nullable()->default(null)->change();
            $table->string('script_container', 191)->default('alpine:3.4', 191)->change();
            $table->string('script_entry', 191)->default('ash', 191)->change();
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid', 191)->nullable()->default(null)->change();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('queue', 191)->change();
        });

        Schema::table('migrations', function (Blueprint $table) {
            $table->string('migration', 191)->change();
        });

        Schema::table('mounts', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('source', 191)->change();
            $table->string('target', 191)->change();
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('fqdn', 191)->change();
            $table->string('scheme', 191)->default('https', 191)->change();
            $table->string('daemon_sftp_alias', 191)->nullable()->default(null)->change();
            $table->string('daemon_base', 191)->change();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->string('id', 191)->change();
            $table->string('type', 191)->change();
            $table->string('notifiable_type', 191)->change();
        });

        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('email', 191)->change();
            $table->string('token', 191)->change();
        });

        Schema::table('recovery_tokens', function (Blueprint $table) {
            $table->string('token', 191)->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('cron_day_of_week', 191)->change();
            $table->string('cron_month', 191)->change();
            $table->string('cron_day_of_month', 191)->change();
            $table->string('cron_hour', 191)->change();
            $table->string('cron_minute', 191)->change();
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->string('external_id', 191)->nullable()->default(null)->change();
            $table->string('name', 191)->change();
            $table->string('status', 191)->nullable()->default(null)->change();
            $table->string('threads', 191)->nullable()->default(null)->change();
            $table->string('image', 191)->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id', 191)->change();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('key', 191)->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('action', 191)->change();
        });

        Schema::table('user_ssh_keys', function (Blueprint $table) {
            $table->string('name', 191)->change();
            $table->string('fingerprint', 191)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('external_id', 191)->nullable()->default(null)->change();
            $table->string('username', 191)->change();
            $table->string('email', 191)->change();
            $table->string('name_first', 191)->nullable()->default(null)->change();
            $table->string('name_last', 191)->nullable()->default(null)->change();
            $table->string('remember_token', 191)->nullable()->default(null)->change();
        });
    }
};
