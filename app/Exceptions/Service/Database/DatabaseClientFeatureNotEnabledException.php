<?php

namespace App\Exceptions\Service\Database;

use App\Exceptions\PanelException;

class DatabaseClientFeatureNotEnabledException extends PanelException
{
    public function __construct()
    {
        parent::__construct('Client database creation is not enabled in this Panel.');
    }
}
