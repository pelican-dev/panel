<?php

namespace App\Exceptions\Http;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class HttpForbiddenException extends HttpException
{
    /**
     * HttpForbiddenException constructor.
     */
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_FORBIDDEN, $message, $previous);
    }
}
