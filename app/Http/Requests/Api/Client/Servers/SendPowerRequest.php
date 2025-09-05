<?php

namespace App\Http\Requests\Api\Client\Servers;

use App\Models\Permission;
use App\Http\Requests\Api\Client\ClientApiRequest;

class SendPowerRequest extends ClientApiRequest
{
    /**
     * Determine if the user has permission to send a power command to a server.
     */
    public function permission(): string
    {
        switch ($this->input('signal')) {
            case 'start':
                return Permission::ACTION_CONTROL_START;
            case 'stop':
            case 'kill':
                return Permission::ACTION_CONTROL_STOP;
            case 'restart':
                return Permission::ACTION_CONTROL_RESTART;
        }

        // Fallback for invalid signals
        return Permission::ACTION_WEBSOCKET_CONNECT;
    }

    /**
     * Rules to validate this request against.
     */
    public function rules(): array
    {
        return [
            'signal' => 'required|string|in:start,stop,restart,kill',
        ];
    }
}
