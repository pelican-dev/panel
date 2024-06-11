<?php

namespace App\Http\Requests\Admin;

use App\Models\Server;
use Illuminate\Validation\Validator;

class ServerFormRequest extends AdminFormRequest
{
    /**
     * Rules to be applied to this request.
     */
    public function rules(): array
    {
        $rules = Server::getRules();
        $rules['description'][] = 'nullable';
        $rules['custom_image'] = 'sometimes|nullable|string';

        return $rules;
    }

    /**
     * Run validation after the rules above have been applied.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $validator->sometimes('node_id', 'required|numeric|bail|exists:nodes,id', function ($input) {
                return !$input->auto_deploy;
            });
        });
    }
}
