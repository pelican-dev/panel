<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Helpers\SoftwareVersionService;

class InfoCommand extends Command
{
    protected $description = 'Displays the application, database, email and backup configurations along with the panel version.';

    protected $signature = 'p:info';

    /**
     * InfoCommand constructor.
     */
    public function __construct(private SoftwareVersionService $versionService)
    {
        parent::__construct();
    }

    /**
     * Handle execution of command.
     */
    public function handle(): void
    {
        $this->output->title('Version Information');
        $this->table([], [
            ['Panel Version', $this->versionService->currentPanelVersion()],
            ['Latest Version', $this->versionService->latestPanelVersion()],
            ['Up-to-Date', $this->versionService->isLatestPanel() ? 'Yes' : $this->formatText('No', 'bg=red')],
        ], 'compact');

        $this->output->title('Application Configuration');
        $this->table([], [
            ['Environment', config('app.env') === 'production' ? config('app.env') : $this->formatText(config('app.env'), 'bg=red')],
            ['Debug Mode', config('app.debug') ? $this->formatText('Yes', 'bg=red') : 'No'],
            ['Application Name', config('app.name')],
            ['Application URL', config('app.url')],
            ['Installation Directory', base_path()],
            ['Cache Driver', config('cache.default')],
            ['Queue Driver', config('queue.default') === 'sync' ? $this->formatText(config('queue.default'), 'bg=red') : config('queue.default')],
            ['Session Driver', config('session.driver')],
            ['Filesystem Driver', config('filesystems.default')],
        ], 'compact');

        $this->output->title('Database Configuration');
        $driver = config('database.default');
        if ($driver === 'sqlite') {
            $this->table([], [
                ['Driver', $driver],
                ['Database', config("database.connections.$driver.database")],
            ], 'compact');
        } else {
            $this->table([], [
                ['Driver', $driver],
                ['Host', config("database.connections.$driver.host")],
                ['Port', config("database.connections.$driver.port")],
                ['Database', config("database.connections.$driver.database")],
                ['Username', config("database.connections.$driver.username")],
            ], 'compact');
        }

        $this->output->title('Email Configuration');
        $driver = config('mail.default');
        if ($driver === 'smtp') {
            $this->table([], [
                ['Driver', $driver],
                ['Host', config("mail.mailers.$driver.host")],
                ['Port', config("mail.mailers.$driver.port")],
                ['Username', config("mail.mailers.$driver.username")],
                ['Encryption', config("mail.mailers.$driver.encryption")],
                ['From Address', config('mail.from.address')],
                ['From Name', config('mail.from.name')],
            ], 'compact');
        } else {
            $this->table([], [
                ['Driver', $driver],
                ['From Address', config('mail.from.address')],
                ['From Name', config('mail.from.name')],
            ], 'compact');
        }

        $this->output->title('Backup Configuration');
        $driver = config('backups.default');
        if ($driver === 's3') {
            $this->table([], [
                ['Driver', $driver],
                ['Region', config("backups.disks.$driver.region")],
                ['Bucket', config("backups.disks.$driver.bucket")],
                ['Endpoint', config("backups.disks.$driver.endpoint")],
                ['Use path style endpoint', config("backups.disks.$driver.use_path_style_endpoint") ? 'Yes' : 'No'],
            ], 'compact');
        } else {
            $this->table([], [
                ['Driver', $driver],
            ], 'compact');
        }
    }

    /**
     * Format output in a Name: Value manner.
     */
    private function formatText(string $value, string $opts = ''): string
    {
        return sprintf('<%s>%s</>', $opts, $value);
    }
}
