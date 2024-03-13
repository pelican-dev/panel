<?php

namespace App\Exceptions\Service;

use Illuminate\Http\Response;
use App\Exceptions\DisplayException;

class HasActiveServersException extends DisplayException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
