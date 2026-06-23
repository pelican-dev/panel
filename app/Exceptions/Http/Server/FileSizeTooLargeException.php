<?php

namespace App\Exceptions\Http\Server;

use App\Exceptions\DisplayException;

class FileSizeTooLargeException extends DisplayException
{
    /**
     * FileSizeTooLargeException constructor.
     */
    public function __construct()
    {
        parent::__construct(trans('exceptions.server.file_too_large'));
    }
}
