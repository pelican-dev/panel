<?php

namespace App\Http\Requests\Api\Client;

use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Application\ApplicationApiRequest;
use App\Models\Server;

class ClientApiRequest extends ApplicationApiRequest
{
    /**
     * Determine if the current user is authorized to perform the requested action against the API.
     */
    public function authorize(): bool
    {
        if ($this instanceof ClientPermissionsRequest || method_exists($this, 'permission')) {
            $server = $this->route()->parameter('server');

            if ($server instanceof Server) {
                return $this->user()->can($this->permission(), $server);
            }

            // If there is no server available on the request, trigger a failure since
            // we expect there to be one at this point.
            return false;
        }

        return true;
    }
}
