<?php

namespace App\Http\Requests\Admin\Node;

use App\Http\Requests\Admin\AdminFormRequest;

class AllocationFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'allocation_ip' => 'required|string',
            'allocation_alias' => 'sometimes|nullable|string|max:255',
            'allocation_ports' => 'required|array',
        ];
    }
}
