<?php

namespace App\Traits\Commands;

use App\Traits\CheckMigrationsTrait;
use Illuminate\Console\Command;

/**
 * @mixin Command
 */
trait RequiresDatabaseMigrations
{
    use CheckMigrationsTrait;

    /**
     * Throw a massive error into the console to hopefully catch the users attention and get
     * them to properly run the migrations rather than ignoring  other previous
     * errors...
     */
    protected function showMigrationWarning(): void
    {
        $this->getOutput()->writeln('<options=bold>
| @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ |
|                                                                              |
|               Your database has not been properly migrated!                  |
|                                                                              |
| @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ |</>

You must run the following command to finish migrating your database:

  <fg=green;options=bold>php artisan migrate --step --force</>

You will not be able to use the Panel as expected without fixing your
database state by running the command above.
');

        $this->getOutput()->error('You must correct the error above before continuing.');
    }
}
