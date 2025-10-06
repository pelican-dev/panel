<?php

namespace App\Http\Controllers\Api\Client\Servers;

use App\Facades\Activity;
use App\Http\Controllers\Api\Client\ClientApiController;
use App\Http\Requests\Api\Client\Servers\SendCommandRequest;
use App\Models\Server;
use Dedoc\Scramble\Attributes\Group;
use Exception;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[Group('Server', weight: 1)]
class CommandController extends ClientApiController
{
    /**
     * Send command
     *
     * Send a command to a running server.
     *
     * @throws ConnectionException
     */
    public function index(SendCommandRequest $request, Server $server): Response
    {
        try {
            $server->send($request->input('command'));
        } catch (Exception $exception) {
            $previous = $exception->getPrevious();

            if ($previous instanceof BadResponseException) {
                if ($previous->getResponse()->getStatusCode() === Response::HTTP_BAD_GATEWAY) {
                    throw new HttpException(Response::HTTP_BAD_GATEWAY, 'Server must be online in order to send commands.', $exception);
                }
            }

            throw $exception;
        }

        Activity::event('server:console.command')
            ->property('command', $request->input('command'))
            ->log();

        return $this->returnNoContent();
    }
}
