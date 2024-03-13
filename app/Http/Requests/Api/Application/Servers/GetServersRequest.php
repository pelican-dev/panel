<?php

namespace App\Http\Requests\Api\Application\Servers;

class GetServersRequest extends GetServerRequest
{
    public function rules(): array
    {
        return [
            'search' => 'string|max:100',
        ];
    }
}
