<?php

namespace App\Console\Commands\User;

use App\Exceptions\Model\DataValidationException;
use App\Models\User;
use Illuminate\Console\Command;

class DisableTwoFactorCommand extends Command
{
    protected $description = 'Disable two-factor authentication for a specific user in the Panel.';

    protected $signature = 'p:user:disable2fa {--email= : The email of the user to disable 2-Factor for.}';

    /**
     * Handle command execution process.
     *
     * @throws DataValidationException
     */
    public function handle(): void
    {
        if ($this->input->isInteractive()) {
            $this->output->warning(trans('command/messages.user.2fa_help_text'));
        }

        $email = $this->option('email') ?? $this->ask(trans('command/messages.user.ask_email'));

        $user = User::where('email', $email)->firstOrFail();
        $user->update([
            'mfa_app_secret' => null,
            'mfa_app_recovery_codes' => null,
            'mfa_email_enabled' => false,
        ]);

        $this->info(trans('command/messages.user.2fa_disabled', ['email' => $user->email]));
    }
}
