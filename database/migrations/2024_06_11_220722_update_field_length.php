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
            $table->string('subject_type', 255)->change();
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('event', 255)->change();
            $table->string('ip', 255)->change();
            $table->string('actor_type', 255)->nullable()->default(null)->change();
        });

        Schema::table('allocations', function (Blueprint $table) {
            $table->string('ip', 255)->change();
            $table->string('notes', 255)->nullable()->default(null)->change();
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->string('action', 255)->change();
            $table->string('subaction', 255)->nullable()->default(null)->change();
        });

        Schema::table('backups', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('disk', 255)->change();
            $table->string('checksum', 255)->nullable()->default(null)->change();
        });

        Schema::table('database_hosts', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('host', 255)->change();
            $table->string('username', 255)->change();
        });

        Schema::table('databases', function (Blueprint $table) {
            $table->string('database', 255)->change();
            $table->string('username', 255)->change();
            $table->string('remote', 255)->default('%', 255)->change();
        });

        Schema::table('egg_variables', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('env_variable', 255)->change();
        });

        Schema::table('eggs', function (Blueprint $table) {
            $table->string('author', 255)->change();
            $table->string('name', 255)->change();
            $table->string('config_stop', 255)->nullable()->default(null)->change();
            $table->string('script_container', 255)->default('alpine:3.4', 255)->change();
            $table->string('script_entry', 255)->default('ash', 255)->change();
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid', 255)->nullable()->default(null)->change();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->string('queue', 255)->change();
        });

        Schema::table('migrations', function (Blueprint $table) {
            $table->string('migration', 255)->change();
        });

        Schema::table('mounts', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('source', 255)->change();
            $table->string('target', 255)->change();
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('fqdn', 255)->change();
            $table->string('scheme', 255)->default('https', 255)->change();
            $table->string('daemon_sftp_alias', 255)->nullable()->default(null)->change();
            $table->string('daemon_base', 255)->change();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->string('id', 255)->change();
            $table->string('type', 255)->change();
            $table->string('notifiable_type', 255)->change();
        });

        Schema::table('password_resets', function (Blueprint $table) {
            $table->string('email', 255)->change();
            $table->string('token', 255)->change();
        });

        Schema::table('recovery_tokens', function (Blueprint $table) {
            $table->string('token', 255)->change();
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('cron_day_of_week', 255)->change();
            $table->string('cron_month', 255)->change();
            $table->string('cron_day_of_month', 255)->change();
            $table->string('cron_hour', 255)->change();
            $table->string('cron_minute', 255)->change();
        });

        Schema::table('servers', function (Blueprint $table) {
            $table->string('external_id', 255)->nullable()->default(null)->change();
            $table->string('name', 255)->change();
            $table->string('status', 255)->nullable()->default(null)->change();
            $table->string('threads', 255)->nullable()->default(null)->change();
            $table->string('image', 255)->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id', 255)->change();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->string('key', 255)->change();
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('action', 255)->change();
        });

        Schema::table('user_ssh_keys', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('fingerprint', 255)->change();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('external_id', 255)->nullable()->default(null)->change();
            $table->string('username', 255)->change();
            $table->string('email', 255)->change();
            $table->string('name_first', 255)->nullable()->default(null)->change();
            $table->string('name_last', 255)->nullable()->default(null)->change();
            $table->string('remember_token', 255)->nullable()->default(null)->change();
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
