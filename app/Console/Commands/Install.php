<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\Helpers\AvailableLanguages;

class Install extends Command
{
    use AvailableLanguages;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run when installing your panel for the first time';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $dependencies = array("");

        $this->output->comment(__("commands.install.first_commit"));
        $first_question = $this->choice(__("commands.install.first_question"), [
            __("commands.install.yes"),
            __("commands.install.no_first"),
        ]);

        if ($first_question === __("commands.install.yes_first")) {
            $question = $this->choice(__("commands.install.first_question"), [
                __("commands.install.yes_second"),
                __("commands.install.no_second"),
            ]);
            if ($question === __("commands.install.yes_second")) {
                $this->InstallDependency("sudo apt update");
                $this->InstallDependency("sudo apt install nginx curl tar"); // php8.2 php8.2-gd php8.2-mysql php8.2-mbstring php8.2-bcmath php8.2-xml php8.2-curl php8.2-zip php8.2-intl php8.2-fpm mysql-server
                $this->InstallDependency("sudo add-apt-repository ppa:ondrej/php");
                $this->InstallDependency("curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer");
            }
        }
    }

    /**
     * @param string $command
     */
    private function InstallDependency($command)
    {
        exec("which $command", $output, $returnCode);

        return $output;
    }
}
