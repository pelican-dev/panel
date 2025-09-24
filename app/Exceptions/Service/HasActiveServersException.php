<?php

namespace App\Exceptions\Service;

use App\Exceptions\DisplayException;
use Illuminate\Http\Response;

class HasActiveServersException extends DisplayException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
