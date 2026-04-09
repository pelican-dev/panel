<?php

namespace App\Http\Requests\Api\Remote;

use Illuminate\Http\Request;

class InstallationDataRequest extends Request
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
