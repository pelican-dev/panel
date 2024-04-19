<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Traits\Commands\EnvironmentWriterTrait;

class Debug extends Command
{
    use EnvironmentWriterTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:debug {--enable : enable debug mode} {--disable : disable debug mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable or disable debug mode.';
    protected array $variables = [];

    /**
     * Handle command execution.
     *
     * @return int
     */
    public function handle()
    {
        /*
        $envFilePath = base_path('.env');

        if (!file_exists($envFilePath)) {
            $this->error('.env file not found.');
            return 1;
        }
        */

        $enable = $this->option('enable');
        $disable = $this->option('disable');
        //$envContents = file_get_contents($envFilePath);

        if ($enable) {
            Artisan::call('down');
            //$envContents = str_replace('APP_DEBUG=false', 'APP_DEBUG=true', $envContents);
            //file_put_contents($envFilePath, $envContents);
            $this->variables['APP_DEBUG'] = 'true';
            $this->writeToEnvironment($this->variables);
            Artisan::call('up');
            $this->info('Debug mode enabled.');

            return 0;
        } elseif ($disable) {
            Artisan::call('down');
            //$envContents = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', $envContents); // No longer needed
            //file_put_contents($envFilePath, $envContents); // No longer needed
            $this->variables['APP_DEBUG'] = 'false';
            $this->writeToEnvironment($this->variables);
            Artisan::call('up');
            $this->info('Debug mode disabled.');

            return 0;
        }

        $question = $this->choice('What do you want to do with debug mode?', [
            'Enable it',
            'Disable it',
            'Cancel command',
        ]);

        if ($question === 'Enable it') {
            Artisan::call('down');
            //$envContents = str_replace('APP_DEBUG=false', 'APP_DEBUG=true', $envContents);
            $this->variables['APP_DEBUG'] = 'true';
            $this->info('Debug mode enabled.');
            Artisan::call('up');
        } elseif ($question === 'Disable it') {
            Artisan::call('down');
            //$envContents = str_replace('APP_DEBUG=true', 'APP_DEBUG=false', $envContents);
            $this->variables['APP_DEBUG'] = 'false';
            $this->info('Debug mode disabled.');
            Artisan::call('up');
        } elseif ($question === 'Cancel command') {
            $this->info('Command successfully canceled.');

            return 0;
        } else {
            $this->error('Invalid choice. Debug mode is unchanged.');

            return 1;
        }

        //file_put_contents($envFilePath, $envContents);
        $this->writeToEnvironment($this->variables);

        return 0;
    }
}
