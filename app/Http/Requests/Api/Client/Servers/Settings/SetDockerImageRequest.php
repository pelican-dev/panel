<?php

namespace App\Http\Requests\Api\Client\Servers\Settings;

use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Client\ClientApiRequest;
use App\Models\Permission;
use App\Models\Server;
use Illuminate\Validation\Rule;
use Webmozart\Assert\Assert;

class SetDockerImageRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_STARTUP_DOCKER_IMAGE;
    }

    public function rules(): array
    {
        /** @var Server $server */
        $server = $this->route()->parameter('server');

        Assert::isInstanceOf($server, Server::class);

        return [
            'docker_image' => ['required', 'string', Rule::in(array_values($server->egg->docker_images))],
        ];
    }
}
