<?php

namespace App\Http\Requests\Api\Remote;

class InstallationDataRequest extends ServerRequest
{
    /**
     * @return array<string, string|string[]>
     */
    public function rules(): array
    {
        return [
            'successful' => 'present|boolean',
            'reinstall' => 'sometimes|boolean',
        ];
    }
}
