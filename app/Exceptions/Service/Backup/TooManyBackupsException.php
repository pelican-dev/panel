<?php

namespace App\Exceptions\Service\Backup;

use App\Exceptions\DisplayException;

class TooManyBackupsException extends DisplayException
{
    /**
     * TooManyBackupsException constructor.
     */
    public function __construct(int $backupLimit)
    {
        parent::__construct(
            sprintf('Cannot create a new backup, this server has reached its limit of %d backups.', $backupLimit)
        );
    }
}
