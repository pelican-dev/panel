<?php

namespace App\Console\Commands\User;

use App\Models\User;
use Illuminate\Console\Command;
use Webmozart\Assert\Assert;

class DeleteUserCommand extends Command
{
    protected $description = 'Deletes a user from the Panel if no servers are attached to their account.';

    protected $signature = 'p:user:delete {--user=}';

    public function handle(): int
    {
        $search = $this->option('user') ?? $this->ask(trans('command/messages.user.search_users'));
        Assert::notEmpty($search, 'Search term should not be empty.');

        $results = User::query()
            ->where('id', 'LIKE', "$search%")
            ->orWhere('username', 'LIKE', "$search%")
            ->orWhere('email', 'LIKE', "$search%")
            ->get();

        if (count($results) < 1) {
            $this->error(trans('command/messages.user.no_users_found'));
            if ($this->input->isInteractive()) {
                return $this->handle();
            }

            return 1;
        }

        if ($this->input->isInteractive()) {
            $tableValues = [];
            foreach ($results as $user) {
                $tableValues[] = [$user->id, $user->email, $user->username];
            }

            $this->table(['User ID', 'Email', 'Name'], $tableValues);
            if (!$deleteUser = $this->ask(trans('command/messages.user.select_search_user'))) {
                return $this->handle();
            }

            $deleteUser = User::query()->findOrFail($deleteUser);
        } else {
            if (count($results) > 1) {
                $this->error(trans('command/messages.user.multiple_found'));

                return 1;
            }

            $deleteUser = $results->first();
        }

        if ($this->confirm(trans('command/messages.user.confirm_delete')) || !$this->input->isInteractive()) {
            $deleteUser->delete();

            $this->info(trans('command/messages.user.deleted'));
        }

        return 0;
    }
}
