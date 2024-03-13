<?php

namespace App\Http\Requests\Api\Client\Servers\Settings;

use Webmozart\Assert\Assert;
use App\Models\Server;
use Illuminate\Validation\Rule;
use App\Models\Permission;
use App\Contracts\Http\ClientPermissionsRequest;
use App\Http\Requests\Api\Client\ClientApiRequest;

class SetDockerImageRequest extends ClientApiRequest implements ClientPermissionsRequest
{
    public function permission(): string
    {
        return Permission::ACTION_STARTUP_DOCKER_IMAGE;
    }

    public function rules(): array
    {
        /** @var \App\Models\Server $server */
        $server = $this->route()->parameter('server');

        Assert::isInstanceOf($server, Server::class);

        return [
            'docker_image' => ['required', 'string', Rule::in(array_values($server->egg->docker_images))],
        ];
    }
}
