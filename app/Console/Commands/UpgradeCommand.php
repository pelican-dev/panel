<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Helper\ProgressBar;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class UpgradeCommand extends Command
{
    protected const DEFAULT_URL = 'https://github.com/pelican-dev/panel/releases/%s/panel.tar.gz';

    protected $signature = 'p:upgrade
        {--user= : The user that PHP runs under. All files will be owned by this user.}
        {--group= : The group that PHP runs under. All files will be owned by this group.}
        {--url= : The specific archive to download.}
        {--release= : A specific version to download from GitHub. Leave blank to use latest.}
        {--skip-download : If set no archive will be downloaded.}
        {--skip-permissions : Skip setting file permissions.}
        {--skip-dependencies : Skip updating dependencies.}
        {--backup : Create a backup of the current state before upgrading.}';

    protected $description = 'Downloads a new archive from GitHub and then executes the normal upgrade commands.';

    protected ProgressBar $progressBar;

    public function handle(): void
    {
        $this->info('Starting the Pelican Panel upgrade process.');

        try {
            $skipDownload = $this->option('skip-download');
            $skipPermissions = $this->option('skip-permissions');
            $skipDependencies = $this->option('skip-dependencies');
            $createBackup = $this->option('backup');

            if ($createBackup) {
                if (!$this->confirm('Do you want to create a backup before upgrading?', true)) {
                    $createBackup = false;
                }
            }

            if ($createBackup) {
                $this->createBackup();
            }

            [$user, $group] = $this->askForUserAndGroup();

            $this->enterMaintenanceMode();
            $this->setProgressBar($skipDownload ? 9 : 10);

            if (!$skipDownload) {
                $this->downloadAndExtractArchive();
            }

            if (!$skipPermissions) {
                $this->setPermissions();
            }

            if (!$skipDependencies) {
                $this->updateDependencies();
            }

            $this->clearCompiledTemplateCache();
            $this->updateDatabaseSchema();
            $this->setOwnership($user, $group);
            $this->restartQueueWorkers();
            $this->exitMaintenanceMode();

            $this->info('Pelican Panel upgrade completed successfully.');
        } catch (\Exception $e) {
            $this->error('Upgrade failed: ' . $e->getMessage());
            Log::error('Upgrade failed', ['exception' => $e]);
        }
    }

    protected function askForUserAndGroup(): array
    {
        $choices = [
            '1' => 'NGINX/Apache (www-data:www-data)',
            '2' => 'Rocky Linux NGINX (nginx:nginx)',
            '3' => 'Rocky Linux Apache (apache:apache)',
        ];

        $choice = $this->choice(
            'What webserver are you using?',
            $choices,
            '1'
        );

        switch ($choice) {
            case '1':
                return ['www-data', 'www-data'];
            case '2':
                return ['nginx', 'nginx'];
            case '3':
                return ['apache', 'apache'];
            default:
                return ['www-data', 'www-data'];
        }
    }

    protected function createBackup(): void
    {
        $this->info('Creating backup...');

        $this->line('Backing up files...');
        $timestamp = date('YmdHis');
        $backupDir = storage_path('backups/' . $timestamp);

        $process = new Process(['mkdir', '-p', $backupDir]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to create backup directory.');
        }

        $process = new Process(['cp', '-r', base_path(), $backupDir]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to backup files.');
        }

        $this->line('Files backed up to ' . $backupDir);

        $this->line('Backing up database...');
        $databaseConfig = Config::get('database.connections.' . Config::get('database.default'));
        $database = $databaseConfig['database'];
        $username = $databaseConfig['username'];
        $password = $databaseConfig['password'];
        $host = $databaseConfig['host'];
        $backupFile = $backupDir . '/database.sql';

        $process = new Process([
            'mysqldump',
            '--user=' . $username,
            '--password=' . $password,
            '--host=' . $host,
            $database,
            '--result-file=' . $backupFile,
        ]);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to backup database.');
        }

        $this->line('Database backed up to ' . $backupFile);
        $this->info('Backup completed.');
    }

    protected function enterMaintenanceMode(): void
    {
        $this->line('$ php artisan down');
        $this->call('down');
    }

    protected function setProgressBar(int $steps): void
    {
        $this->progressBar = $this->output->createProgressBar($steps);
        $this->progressBar->start();
    }

    protected function downloadAndExtractArchive(): void
    {
        $this->progressBar->advance();
        $url = $this->getUrl();
        $this->line('$ curl -L "' . $url . '" | tar -xzv');
        $process = Process::fromShellCommandline('curl -L "' . $url . '" | tar -xzv');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to download or extract the archive.');
        }
    }

    protected function setPermissions(): void
    {
        $this->progressBar->advance();
        $this->line('$ chmod -R 755 storage bootstrap/cache');
        $process = new Process(['chmod', '-R', '755', 'storage', 'bootstrap/cache']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to set permissions.');
        }
    }

    protected function updateDependencies(): void
    {
        $this->progressBar->advance();
        $this->line('$ composer install --no-dev --optimize-autoloader');
        $process = new Process(['composer', 'install', '--no-dev', '--optimize-autoloader']);
        $process->setTimeout(10 * 60);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to update dependencies.');
        }
    }

    protected function clearCompiledTemplateCache(): void
    {
        $this->progressBar->advance();
        $this->line('$ php artisan view:clear');
        $this->call('view:clear');

        $this->progressBar->advance();
        $this->line('$ php artisan config:clear');
        $this->call('config:clear');
    }

    protected function updateDatabaseSchema(): void
    {
        $this->progressBar->advance();
        $this->line('$ php artisan migrate --seed --force');
        $this->call('migrate', ['--force' => true, '--seed' => true]);
    }

    protected function setOwnership(string $user, string $group): void
    {
        $this->progressBar->advance();
        $this->line('$ chown -R ' . $user . ':' . $group . ' *');
        $process = Process::fromShellCommandline('chown -R ' . $user . ':' . $group . ' *', base_path());
        $process->setTimeout(10 * 60);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Failed to set ownership.');
        }
    }

    protected function restartQueueWorkers(): void
    {
        $this->progressBar->advance();
        $this->line('$ php artisan queue:restart');
        $this->call('queue:restart');
    }

    protected function exitMaintenanceMode(): void
    {
        $this->progressBar->advance();
        $this->line('$ php artisan up');
        $this->call('up');
    }

    protected function getUrl(): string
    {
        if ($this->option('url')) {
            return $this->option('url');
        }

        return sprintf(self::DEFAULT_URL, $this->option('release') ? 'download/v' . $this->option('release') : 'latest/download');
    }
}
