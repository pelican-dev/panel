<?php

namespace App\Console;

use App\Console\Commands\Egg\CheckEggUpdatesCommand;
use App\Console\Commands\Maintenance\CleanServiceBackupFilesCommand;
use App\Console\Commands\Maintenance\PruneImagesCommand;
use App\Console\Commands\Maintenance\PruneOrphanedBackupsCommand;
use App\Console\Commands\Schedule\ProcessRunnableCommand;
use App\Jobs\NodeStatistics;
use App\Models\ActivityLog;
use App\Models\Webhook;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // https://laravel.com/docs/10.x/upgrade#redis-cache-tags
        $schedule->command('cache:prune-stale-tags')->hourly();

        // Execute scheduled commands for servers every minute, as if there was a normal cron running.
        $schedule->command(ProcessRunnableCommand::class)->everyMinute()->withoutOverlapping();

        $schedule->command(CleanServiceBackupFilesCommand::class)->daily();
        $schedule->command(PruneImagesCommand::class)->daily();
        $schedule->command(CheckEggUpdatesCommand::class)->hourly();

        $schedule->job(new NodeStatistics())->everyFiveSeconds()->withoutOverlapping();

        if (config('backups.prune_age')) {
            // Every 30 minutes, run the backup pruning command so that any abandoned backups can be deleted.
            $schedule->command(PruneOrphanedBackupsCommand::class)->everyThirtyMinutes();
        }

        if (config('activity.prune_days')) {
            $schedule->command(PruneCommand::class, ['--model' => [ActivityLog::class]])->daily();
        }

        if (config('panel.webhook.prune_days')) {
            $schedule->command(PruneCommand::class, ['--model' => [Webhook::class]])->daily();
        }

        $schedule->command(ScheduleCheckHeartbeatCommand::class)->everyMinute();
        $schedule->command(RunHealthChecksCommand::class)->everyFiveMinutes();
    }
}
