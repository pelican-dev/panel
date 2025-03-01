<?php

namespace App\Console\Commands\Overrides;

use Illuminate\Foundation\Console\KeyGenerateCommand as BaseKeyGenerateCommand;

class KeyGenerateCommand extends BaseKeyGenerateCommand
{
    /**
     * Override the default Laravel key generation command to throw a warning to the user
     * if it appears that they have already generated an application encryption key.
     */
    public function handle(): void
    {
        if (!empty(config('app.key')) && $this->input->isInteractive()) {
            $this->output->warning(trans('commands.key_generate.error_already_exist'));
            if (!$this->confirm(trans('commands.key_generate.understand'))) {
                return;
            }

            if (!$this->confirm(trans('commands.key_generate.continue'))) {
                return;
            }
        }

        parent::handle();
    }
}
